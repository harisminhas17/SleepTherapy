<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SleepingDisorder extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
  protected $table = 'sleeping_disorder';
  protected $guarded = [];

    /**
     * Get the users associated with the sleeping disorder.
     */
    public function users(): HasMany
    {
        return $this->hasMany(UserSleepingDisorder::class);
    }
}
