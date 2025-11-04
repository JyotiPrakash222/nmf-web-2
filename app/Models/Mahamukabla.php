<?php

namespace App\Models;
use \App\Models\Party;

use Illuminate\Database\Eloquent\Model;

class Mahamukabla extends Model
{
    protected $table = 'mahamukabla';

    protected $fillable = [
        'party_id',
        'candidate_id',
        'slide_image',
        'sequence'
    ];

    // Relationships
    public function party()
    {
        return $this->belongsTo(Party::class, 'party_id'); //  Updated
    }

    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }
}
