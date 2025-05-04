<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'User';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'api_token',
    ];

    /**
     * Create a new API token for the user.
     *
     * @return string
     */
    public function createToken($name)
    {
        $token = Str::random(60);

        $this->api_token = $token;
        $this->save();

        return $token;
    }

    /**
     * Get the sleeping disorders associated with the user.
     */
    public function sleepingDisorders()
    {
        return $this->hasMany(UserSleepingDisorder::class);
    }

    /**
     * Get the journals associated with the user.
     */
    public function journals(): HasMany
    {
        return $this->hasMany(Journal::class);
    }

    /**
     * Get the challenges associated with the user.
     */
    public function challenges(): HasMany
    {
        return $this->hasMany(UserChallenge::class);
    }

    /**
     * Get the alarms associated with the user.
     */
    public function alarms(): HasMany
    {
        return $this->hasMany(Alarm::class);
    }
}
