<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionDetail extends Model
{
    protected $guarded = [];

    public function production()
    {
        return $this->belongsTo(Production::class);
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function secondKarigar()
    {
        return $this->belongsTo(Karigar::class, 'second_karigar_id');
    }
}
