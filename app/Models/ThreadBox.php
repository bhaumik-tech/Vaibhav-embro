<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThreadBox extends Model
{
    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(ThreadBoxItem::class);
    }
}
