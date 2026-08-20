<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InputChalanItem extends Model
{
    protected $fillable = ['input_chalan_id', 'chart', 'detail', 'mtr', 'note', 'pcs', 'bundles'];

    public function inputChalan()
    {
        return $this->belongsTo(InputChalan::class);
    }
}
