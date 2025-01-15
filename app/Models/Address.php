<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{

    protected $table = 'addresses';
    protected $fillable = [
        'user_id',
        'label',
        'receiver_name',
        'phone_number',
        'full_address',
        'is_primary'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
