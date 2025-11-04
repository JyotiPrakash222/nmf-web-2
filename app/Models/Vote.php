<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = ['title', 'user_ip'];

    public function options()
    {
        return $this->hasMany(VoteOption::class);
    }
}
?>
