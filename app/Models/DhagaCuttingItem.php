<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DhagaCuttingItem extends Model
{
    protected $fillable = [
        'dhaga_cutting_id',
        'rate_label',
        'rate_value',
        'pieces',
        'amount',
    ];

    public function dhagaCutting()
    {
        return $this->belongsTo(DhagaCutting::class);
    }
}
