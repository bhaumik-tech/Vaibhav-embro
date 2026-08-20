<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenerateBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'firm_id',
        'party_id',
        'bill_no',
        'date',
        'name',
        'add',
        'gst',
        'vatav_percent',
        'sgst_percent',
        'cgst_percent',
        'tds_percent',
        'payment_date',
        'payment_detail',
    ];

    public function getTotalPcsAttribute()
    {
        return $this->items->sum('pcs');
    }

    public function getTotalAmountAttribute()
    {
        return $this->items->sum('amount');
    }

    public function getGstAmountAttribute()
    {
        $total = $this->total_amount;
        $vatavAmount = $total * ($this->vatav_percent / 100);
        $taxable = $total - $vatavAmount;
        $sgst = $taxable * ($this->sgst_percent / 100);
        $cgst = $taxable * ($this->cgst_percent / 100);
        return $sgst + $cgst;
    }

    public function getNetAmountAttribute()
    {
        $total = $this->total_amount;
        $vatavAmount = $total * ($this->vatav_percent / 100);
        $taxable = $total - $vatavAmount;
        $sgst = $taxable * ($this->sgst_percent / 100);
        $cgst = $taxable * ($this->cgst_percent / 100);
        $tds = $taxable * ($this->tds_percent / 100);
        return round($taxable + $sgst + $cgst - $tds);
    }

    public function items()
    {
        return $this->hasMany(GenerateBillItem::class);
    }

    public function firm()
    {
        return $this->belongsTo(Firm::class);
    }

    public function party()
    {
        return $this->belongsTo(Party::class);
    }
}
