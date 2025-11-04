<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElectionResult extends Model
{
    // Define the correct table name
    protected $table = 'election_results';

    // Define which fields are mass assignable
protected $fillable = [
    'party_name',
    'abbreviation',
    'alliance',
    'seats_won',    //W
    'percentage',
    'party_logo',
    'show_in_list',
    'show_in_highlight',
    'seat_lose',  //L
    'total_seats',   //W+L
];
    

    
}
