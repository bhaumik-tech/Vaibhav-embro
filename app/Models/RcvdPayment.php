<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RcvdPayment extends Model
{
    protected $fillable = [
        'cheque_no',
        'payment_type',
        'date',
        'party_id',
        'firm_id',
        'amount',
        'bill_month',
        'bill_no',
        'cheque_photo',
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
