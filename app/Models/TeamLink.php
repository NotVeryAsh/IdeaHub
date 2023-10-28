<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamLink extends Model
{
    use HasFactory;

    public $fillable = [
        'token',
        'team_id',
        'expires_at',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
