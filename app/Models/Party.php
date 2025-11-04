<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    protected $table = 'parties';

    protected $fillable = [
        'party_name',
        'abbreviation',
        'alliance',
        'party_logo'
    ];
}
