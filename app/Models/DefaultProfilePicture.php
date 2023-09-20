<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class DefaultProfilePicture extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'path',
    ];
}
