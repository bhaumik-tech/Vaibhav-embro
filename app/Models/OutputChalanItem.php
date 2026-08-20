<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OutputChalanItem extends Model
{
    protected $guarded = [];

    public function outputChalan()
    {
        return $this->belongsTo(OutputChalan::class);
    }
}
