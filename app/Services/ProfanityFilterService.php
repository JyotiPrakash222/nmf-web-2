<?php
// app/Services/ProfanityFilterService.php

namespace App\Services;

use App\Models\BadWord;
use Illuminate\Support\Facades\Cache;

class ProfanityFilterService
{
    protected $badWords;
    protected $replacementChar = '*';

    public function __construct()
    {
        $this->badWords = $this->getBadWords();
    }

    /**
     * Get cached bad words from database
     */
    protected function getBadWords()
    {
        return Cache::remember('bad_words_list', 3600, function () {
            return BadWord::where('is_active', true)
                ->pluck('word')
                ->map(function ($word) {
                    return strtolower(trim($word));
                })
                ->toArray();
        });
    }

    /**
     * Check if text contains profanity
     */
    public function containsProfanity($text)
    {
        $result = $this->analyzeText($text);
        return $result['containsProfanity'];
    }

    /**
     * Get flagged words from text
     */
    public function getFlaggedWords($text)
    {
        $result = $this->analyzeText($text);
        return $result['flaggedWords'];
    }

    /**
     * Clean text by replacing bad words
     */
    public function cleanText($text)
    {
        $cleanText = $text;
        $flaggedWords = $this->getFlaggedWords($text);

        foreach ($flaggedWords as $word) {
            $replacement = $this->smartMask($word);
            $cleanText = $this->replaceWord($cleanText, $word, $replacement);
        }
        \Log::debug("Cleaned text in filter service: " . $cleanText);
        return $cleanText;
    }


    /**
     * Analyze text for profanity
     */
    protected function analyzeText($text)
    {
        $flaggedWords = [];
        $textLower = strtolower($text);

        // Remove special characters and normalize text
        $normalizedText = $this->normalizeText($textLower);

        foreach ($this->badWords as $badWord) {
            if ($this->findBadWord($normalizedText, $badWord)) {
                $flaggedWords[] = $badWord;
            }
        }

        return [
            'containsProfanity' => !empty($flaggedWords),
            'flaggedWords' => array_unique($flaggedWords),
            'flaggedWordsCount' => count(array_unique($flaggedWords))
        ];
    }

    /**
     * Mask a word in the "f*ck" => "f*uk", "spam" => "s**m" style.
     */
    protected function smartMask($word)
    {
        $len = mb_strlen($word);
        if ($len <= 2) {
            return str_repeat($this->replacementChar, $len);
        } elseif ($len === 3) {
            return mb_substr($word, 0, 1) . $this->replacementChar . mb_substr($word, -1);
        } elseif ($len === 4) {
            return mb_substr($word, 0, 1)
                . $this->replacementChar
                . mb_substr($word, 2, 1)
                . mb_substr($word, -1);
        } elseif ($len <= 6) {
            return mb_substr($word, 0, 2)
                . str_repeat($this->replacementChar, $len - 3)
                . mb_substr($word, -1);
        } else {
            return mb_substr($word, 0, 2)
                . str_repeat($this->replacementChar, $len - 4)
                . mb_substr($word, -2);
        }
    }




    /**
     * Normalize text for analysis
     */
    protected function normalizeText($text)
    {
        // Replace common character substitutions
        $substitutions = [
            '@' => 'a',
            '3' => 'e',
            '1' => 'i',
            '0' => 'o',
            '5' => 's',
            '$' => 's',
            '7' => 't',
            '4' => 'a',
            '!' => 'i',
            '+' => 't'
        ];

        $normalized = str_replace(array_keys($substitutions), array_values($substitutions), $text);

        // Remove special characters and extra spaces
        $normalized = preg_replace('/[^a-z\s]/', ' ', $normalized);
        $normalized = preg_replace('/\s+/', ' ', $normalized);

        return trim($normalized);
    }

    /**
     * Find bad word in text with various patterns
     */
    protected function findBadWord($text, $badWord)
    {
    $letters = preg_split('//u', $badWord, -1, PREG_SPLIT_NO_EMPTY);
    
    $pattern = '';
    foreach ($letters as $letter) {
        $pattern .= '(' . preg_quote($letter, '/') . ')+';
    }
    
    return preg_match('/\b' . $pattern . '\b/i', $text);
}

    /**
     * Replace word in text while preserving case
     */
    protected function replaceWord($text, $search, $replace)
    {
        return preg_replace_callback(
            '/\b' . preg_quote($search, '/') . '\b/i',
            function ($matches) use ($replace) {
                return $replace;
            },
            $text
        );
    }

    /**
     * Get severity level of flagged content
     */
    public function getSeverityLevel($flaggedWords)
    {
        if (empty($flaggedWords)) {
            return 'clean';
        }

        $severities = BadWord::whereIn('word', $flaggedWords)
            ->pluck('severity')
            ->toArray();

        if (in_array('high', $severities)) {
            return 'high';
        } elseif (in_array('medium', $severities)) {
            return 'medium';
        } else {
            return 'low';
        }
    }

    /**
     * Determine if comment should be auto-approved
     */
    public function shouldAutoApprove($text)
    {
        $analysis = $this->analyzeText($text);

        if (!$analysis['containsProfanity']) {
            return true; // No profanity, auto-approve
        }

        $severity = $this->getSeverityLevel($analysis['flaggedWords']);

        // Auto-approve if severity is low or medium, only pending if high
        return in_array($severity, ['clean', 'low', 'medium']);
    }

}
