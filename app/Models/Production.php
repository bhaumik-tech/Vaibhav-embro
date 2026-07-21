<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    protected $guarded = [];

    public function karigar()
    {
        return $this->belongsTo(Karigar::class);
    }

    public function details()
    {
        return $this->hasMany(ProductionDetail::class);
    }
}
