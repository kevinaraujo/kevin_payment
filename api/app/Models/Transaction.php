<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    const STATUS_PENDING = 'PENDING';

    protected $fillable = [
        'payer_id', 'payee_id', 'user_payment_id', 'value', 'description', 'status'
    ];
}
