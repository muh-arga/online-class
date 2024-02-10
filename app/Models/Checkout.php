<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Checkout extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'camp_id',
        'payment_status',
        'midtrans_url',
        'midtrans_booking_code',
        'discount_id',
        'discount_precentage',
        'total'
    ];

    public function setExpiredAttribute($value)
    {
        $this->attributes['expired'] = date('Y-m-t', strtotime($value));
    }

    public function Camp()
    {
        return $this->belongsTo(Camp::class, 'camp_id', 'id');
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function Discount()
    {
        return $this->belongsTo(Discount::class, 'discount_id', 'id');
    }
}
