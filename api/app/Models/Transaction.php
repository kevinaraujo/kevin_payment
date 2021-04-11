<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    const STATUS_PENDING = 'PENDING';
    const STATUS_SUCCESS = 'SUCCESS';
    const STATUS_ERROR = 'ERROR';

    protected $fillable = [
        'payer_id', 'payee_id', 'user_payment_id', 'value', 'description', 'status', 'status_message'
    ];
}
