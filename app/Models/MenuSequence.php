<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuSequence extends Model
{
    use HasFactory;
    protected  $guarded = []; 
    public function type()
    {
        return $this->belongsTo(MenuType::class);
    }

    public function category()
    {
        return $this->belongsTo(MenuCategory::class);
    }
    // App\Models\Menu.php
    public function children()
    {
        return $this->hasMany(Menu::class, 'menu_id', 'id')->orderBy('sequence_id');
    }

}
