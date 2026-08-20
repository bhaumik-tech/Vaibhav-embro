<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $guarded = [];

    public function firm()
    {
        return $this->belongsTo(Firm::class);
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function party()
    {
        return $this->belongsTo(Party::class);
    }
}
