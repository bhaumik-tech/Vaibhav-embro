<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutputChalan extends Model
{
    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
        'is_done' => 'boolean',
    ];

    public function firm()
    {
        return $this->belongsTo(Firm::class);
    }

    public function party()
    {
        return $this->belongsTo(Party::class);
    }

    public function items()
    {
        return $this->hasMany(OutputChalanItem::class);
    }
}
