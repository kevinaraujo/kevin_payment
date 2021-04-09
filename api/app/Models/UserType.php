<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    protected $table = 'users_types';

    protected $fillable = [
        'code', 'description', 'sends_money'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'user_type_id');
    }
}
