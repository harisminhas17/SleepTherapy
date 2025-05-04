<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SleepChallenge extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
   protected $table = 'SleepChallenge';
   protected $guarded = [];

    /**
     * Get the users associated with the challenge.
     */
    public function users(): HasMany
    {
        return $this->hasMany(UserChallenge::class);
    }
}
