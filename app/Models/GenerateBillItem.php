<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenerateBillItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'generate_bill_id',
        'ch_no',
        'details',
        'pcs',
        'rate',
        'amount',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    public function generateBill()
    {
        return $this->belongsTo(GenerateBill::class);
    }
}
