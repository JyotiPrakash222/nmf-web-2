<?php

namespace App\Jobs;

use App\Models\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process; // <-- Import the Process facade
use Throwable;

class ProcessVideoCompression implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $videoId;
    protected $originalPath;
    protected $compressedPath;
    protected $finalPath;

    public $timeout = 3600; // 1 hour job timeout
    public $tries = 2;      // Total attempts for this job

    /**
     * Calculate the number of seconds to wait before retrying the job.
     */
    public function backoff(): array
    {
        return [60, 180]; // Wait 1 min, then 3 mins before retrying
    }

    public function __construct($videoId, $originalPath, $compressedPath, $finalPath)
    {
        $this->videoId = $videoId;
        $this->originalPath = $originalPath;
        $this->compressedPath = $compressedPath;
        $this->finalPath = $finalPath;
    }

    public function handle(): void
    {
        // ** THE FIX IS HERE: Normalize directory separators for the current OS **
        $this->originalPath = str_replace('/', DIRECTORY_SEPARATOR, $this->originalPath);
        $this->compressedPath = str_replace('/', DIRECTORY_SEPARATOR, $this->compressedPath);

        $video = Video::find($this->videoId);

        if (!$video) {
            Log::error("Video compression job failed: Video not found.", ['videoId' => $this->videoId]);
            return;
        }

        Log::info("Starting video processing job.", ['videoId' => $this->videoId]);

        try {
            $video->update(['processing_status' => 'processing']);

            if (!File::exists($this->originalPath)) {
                throw new \Exception("Original video file not found at path: {$this->originalPath}");
            }

            $result = $this->compressVideoWithProcess();

            if ($result['success']) {
                $video->update([
                    'video_path' => $this->finalPath,
                    'file_size' => File::size($this->compressedPath),
                    'processing_status' => 'completed',
                    'is_active' => 1,
                ]);

                if (File::exists($this->originalPath)) {
                    File::delete($this->originalPath);
                }

                Log::info("Video compression successful.", [
                    'videoId' => $this->videoId,
                    'original_size_bytes' => $result['original_size'],
                    'compressed_size_bytes' => $result['compressed_size'],
                    'compression_ratio' => $result['compression_ratio'] . '%'
                ]);

            } else {
                throw new \Exception($result['error']);
            }

        } catch (Throwable $e) {
            Log::error("Video processing job caught an exception.", [
                'videoId' => $this->videoId,
                'error' => $e->getMessage()
            ]);

            $this->fallbackToOriginalFile($video);
            $this->fail($e);
        }
    }

    /**
     * NEW: Robust video compression using Laravel's Process facade.
     * This provides superior error handling and logging.
     */
    private function compressVideoWithProcess(): array
    {
        // This function is now correct from the previous step
        $command = [
            'ffmpeg',
            '-y',
            '-err_detect',
            'ignore_err', // Ignores minor errors in the input
            '-i',
            $this->originalPath,
            '-c:v',
            'libx264',
            '-preset',
            'medium',
            '-crf',
            '28',
            '-movflags',
            'faststart',

            // --- Audio Settings ---
            '-c:a',
            'aac',
            '-b:a',
            '128k',
            '-ar',
            '48000',
            '-ac',
            '2', // <--  line to force 2-channel stereo audio

            '-max_muxing_queue_size',
            '9999',
            '-threads',
            '0',
            $this->compressedPath,
        ];

        Log::info('Executing FFmpeg command.', [
            'videoId' => $this->videoId,
            'command' => implode(' ', $command),
        ]);

        $process = Process::timeout(3500)->run($command);

        if ($process->successful()) {
            if (!File::exists($this->compressedPath) || File::size($this->compressedPath) === 0) {
                Log::error('FFmpeg process succeeded but the output file is missing or empty.', [
                    'videoId' => $this->videoId,
                    'path' => $this->compressedPath,
                    'ffmpeg_output' => $process->output(),
                    'ffmpeg_error_output' => $process->errorOutput(),
                ]);
                return ['success' => false, 'error' => 'Output file is missing or empty.'];
            }

            $originalSize = File::size($this->originalPath);
            // ===================================================================
            // >> THE FIX IS HERE: Changed the dot (.) to an arrow (->) <<
            // ===================================================================
            $compressedSize = File::size($this->compressedPath);

            return [
                'success' => true,
                'original_size' => $originalSize,
                'compressed_size' => $compressedSize,
                'compression_ratio' => $originalSize > 0 ? round((($originalSize - $compressedSize) / $originalSize) * 100, 2) : 0
            ];
        }

        Log::error('FFmpeg process failed.', [
            'videoId' => $this->videoId,
            'exit_code' => $process->exitCode(),
            'ffmpeg_output' => $process->output(),
            'ffmpeg_error_output' => $process->errorOutput(),
        ]);

        return [
            'success' => false,
            'error' => 'FFmpeg process failed. Check logs for details. Error output: ' . $process->errorOutput()
        ];
    }

    /**
     * Handle the job failing after all retries.
     */
    public function failed(Throwable $exception): void
    {
        $video = Video::find($this->videoId);
        if ($video && $video->processing_status !== 'failed_but_active') {
            Log::error("Video compression job failed permanently after all retries.", [
                'videoId' => $this->videoId,
                'error' => $exception->getMessage()
            ]);
            $this->fallbackToOriginalFile($video);
        }
    }

    /**
     * Fallback logic to use the original, uncompressed file.
     */
    private function fallbackToOriginalFile(Video $video): void
    {
        if (File::exists($this->originalPath)) {
            // Move the original file to the final destination
            File::move($this->originalPath, $this->compressedPath);
            $video->update([
                'video_path' => $this->finalPath, // Path is now correct
                'processing_status' => 'failed_but_active', // Custom status
                'is_active' => 1, // Make it available on the site
            ]);
            Log::warning("Used original file as fallback due to compression failure.", ['videoId' => $this->videoId]);
        } else {
            $video->update(['processing_status' => 'failed']);
            Log::error("Compression failed and original file was missing for fallback.", ['videoId' => $this->videoId]);
        }
    }
}