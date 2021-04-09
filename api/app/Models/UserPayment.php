<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPayment extends Model
{
    protected $table = 'users_payments';

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_id');
    }


}
