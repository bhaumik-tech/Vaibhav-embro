<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GenerateChalan extends Model
{
    protected $guarded = [];

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
        return $this->hasMany(GenerateChalanItem::class);
    }
}
