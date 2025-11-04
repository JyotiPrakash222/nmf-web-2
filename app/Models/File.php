<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;
    protected  $fillable  = ['file_name', 'user_id','file_name', 'file_type', 'file_size', 'full_path','duration']; 

    public function getFormattedFileSizeAttribute()
    {
        $bytes = $this->file_size;
        if (!is_numeric($bytes)) {
            return 'Invalid Size';
        }

        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
}
