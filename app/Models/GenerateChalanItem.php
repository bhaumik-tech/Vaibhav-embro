<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GenerateChalanItem extends Model
{
    protected $guarded = [];

    public function chalan()
    {
        return $this->belongsTo(GenerateChalan::class, 'generate_chalan_id');
    }
}
