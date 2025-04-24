<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alarm extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
   protected $table = 'alarm';
   protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    /**
     * Get the user that owns the alarm.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId');
    }

    /**
     * Get the repeat days attribute as an array.
     *
     * @return array<string>
     */
    public function getRepeatDaysAttribute($value)
    {
        return json_decode($value, true) ?? [];
    }

    /**
     * Set the repeat days attribute as a JSON string.
     *
     * @param array<string> $value
     * @return void
     */
    public function setRepeatDaysAttribute($value)
    {
        $this->attributes['repeatDays'] = json_encode($value);
    }
}
