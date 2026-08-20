<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThreadBoxItem extends Model
{
    protected $guarded = [];

    public function threadBox()
    {
        return $this->belongsTo(ThreadBox::class);
    }
}
