<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Journal extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
   protected $table = 'journal';
   protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
