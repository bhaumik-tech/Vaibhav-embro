<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneratedCheque extends Model
{
    protected $fillable = [
        'is_ac_payee',
        'date',
        'payee_name',
        'firm_id',
        'remark',
        'bill_no',
        'amount',
    ];

    protected $casts = [
        'is_ac_payee' => 'boolean',
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function firm()
    {
        return $this->belongsTo(Firm::class);
    }
}
