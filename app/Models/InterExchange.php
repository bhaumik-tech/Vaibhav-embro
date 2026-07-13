<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterExchange extends Model
{
    protected $guarded = [];

    public function aapnarUser()
    {
        return $this->belongsTo(User::class, 'user_aapnar_id');
    }

    public function lenarUser()
    {
        return $this->belongsTo(User::class, 'user_lenar_id');
    }

    public function items()
    {
        return $this->hasMany(InterExchangeItem::class);
    }
}
