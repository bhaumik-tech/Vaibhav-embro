<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'firm_id',
        'party_id',
        'type', // 'received', 'pay'
        'amount',
        'ref_no',
        'remark'
    ];

    public function firm()
    {
        return $this->belongsTo(Firm::class);
    }

    public function party()
    {
        return $this->belongsTo(Party::class);
    }
}
