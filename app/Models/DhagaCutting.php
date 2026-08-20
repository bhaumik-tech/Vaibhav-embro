<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DhagaCutting extends Model
{
    protected $fillable = [
        'person_id',
        'date',
        'remark_note',
        'is_highlighted',
        'total_pieces',
        'total_amount',
    ];

    public function person()
    {
        return $this->belongsTo(DhCuttingPerson::class, 'person_id');
    }

    public function items()
    {
        return $this->hasMany(DhagaCuttingItem::class);
    }
}
