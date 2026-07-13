<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterExchangeItem extends Model
{
    protected $guarded = [];

    public function interExchange()
    {
        return $this->belongsTo(InterExchange::class);
    }
}
