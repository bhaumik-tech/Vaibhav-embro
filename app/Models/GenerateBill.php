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
    ];

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
