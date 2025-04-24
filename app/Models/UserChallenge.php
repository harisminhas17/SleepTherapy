<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserChallenge extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $table = 'user_challenge';
    protected $guarded = [];




    /**
     * Get the user that owns the challenge.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId');
    }

    /**
     * Get the challenge that belongs to the user.
     */
    public function challenge(): BelongsTo
    {
        return $this->belongsTo(SleepChallenge::class, 'challengeId');
    }
}
