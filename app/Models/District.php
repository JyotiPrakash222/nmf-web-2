<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'districts';

    public function scopeJoinState($query){
        $query->leftjoin('states', function($join) {
            $join->on('states.id', '=', 'districts.state_id');
        });
    }
}
