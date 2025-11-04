<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;
    protected $table = 'sub_categories';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
    protected $guarded = [];

    // Relationship with Blog model
    public function blogs()
    {
        return $this->hasMany(Blog::class, 'sub_category_id');
    }
}
