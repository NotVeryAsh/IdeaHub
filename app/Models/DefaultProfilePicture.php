<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefaultProfilePicture extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
    ];
}
