<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GenerateChalan extends Model
{
    protected $guarded = [];

    public function firm()
    {
        return $this->belongsTo(Firm::class);
    }

    public function party()
    {
        return $this->belongsTo(Party::class);
    }

    public function items()
    {
        return $this->hasMany(GenerateChalanItem::class);
    }

    public function getBillNoAttribute()
    {
        $billItems = \App\Models\GenerateBillItem::with('generateBill')
            ->where(function ($query) {
                $query->where('sr_no', $this->chalan_no)
                      ->orWhereRaw('FIND_IN_SET(?, REPLACE(sr_no, " ", ""))', [$this->chalan_no]);
            })
            ->whereHas('generateBill', function ($query) {
                $query->where('party_id', $this->party_id)
                      ->where('is_draft', false);
            })
            ->get();

        if ($billItems->isEmpty()) {
            return null;
        }

        return $billItems->map(function ($item) {
            return $item->generateBill->bill_no;
        })->unique()->filter()->implode(', ');
    }
}
