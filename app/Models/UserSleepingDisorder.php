<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSleepingDisorder extends Model
{
    use HasFactory;
    
    protected $table = 'user_sleeping_disorder';
    protected $guarded = [];

    /**
     * Get the user that owns the disorder.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId');
    }

    /**
     * Get the sleeping disorder that belongs to the user.
     */
    public function sleepingDisorder(): BelongsTo
    {
        return $this->belongsTo(SleepingDisorder::class, 'disorderId');
    }
}
