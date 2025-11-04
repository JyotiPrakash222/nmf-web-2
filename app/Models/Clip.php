<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clip extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'clips';

    public function category()
    {
        return $this->belongsTo(Category::class, 'categories_id');
    }
}
