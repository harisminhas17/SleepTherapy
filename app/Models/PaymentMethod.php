<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $table = 'PaymentMethod';
    protected $guarded = [];

    public function payment()
    {
        return $this->hasMany(Payment::class);
    }
}
