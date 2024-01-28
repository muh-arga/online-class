<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Camp extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'price',
    ];

    public function getIsRegisteredAttribute()
    {
        if(!auth()->check()) return false;

        return Checkout::where('user_id', auth()->id())
            ->where('camp_id', $this->id)
            ->exists();
    }
}
