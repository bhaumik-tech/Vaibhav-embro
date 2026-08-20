<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_no',
        'bill_date',
        'firm_id',
        'company_name',
        'amount_without_gst',
        'gst_percent',
        'gst_rs',
        'amount',
        'remark',
        'image',
        'gst_no',
        'cheque_no',
    ];

    public function firm()
    {
        return $this->belongsTo(Firm::class);
    }
}
