<?php

// app/Models/Viewer.php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Viewer extends Authenticatable
{
    protected $table = 'viewers';
    protected $fillable = ['name','email','password'];
    protected $hidden   = ['password','remember_token'];
    
    // Override remember token methods if you don't want to use remember_token
    public function getRememberToken()
    {
        return null; // or return $this->{$this->getRememberTokenName()};
    }

    public function setRememberToken($value)
    {
        // Do nothing or implement custom logic
    }

    public function getRememberTokenName()
    {
        return null; // or return 'remember_token';
    }
}
