<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InputChalan extends Model
{
    protected $fillable = ['party_id', 'firm_id', 'chalan_no', 'date'];

    public function items()
    {
        return $this->hasMany(InputChalanItem::class);
    }

    public function party()
    {
        return $this->belongsTo(Party::class);
    }

    public function firm()
    {
        return $this->belongsTo(Firm::class);
    }
}
