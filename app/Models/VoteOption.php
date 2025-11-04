<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoteOption extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'vote_options';

    public function vote()
    {
        return $this->belongsTo(Vote::class);
    }
}
?>
