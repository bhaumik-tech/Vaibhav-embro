<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    protected $fillable = ['name', 'address', 'gst_number', 'vatav', 'sgst', 'cgst', 'tds', 'firm_id'];

    public function firms()
    {
        return $this->belongsToMany(Firm::class);
    }

    public static function getPermitted()
    {
        $user = auth()->user();
        if (!$user) return collect();
        if ($user->isAdmin()) return static::orderBy('name')->get();
        
        $permittedFirmIds = \App\Models\Firm::getPermitted()->pluck('id');
        return static::whereHas('firms', function($q) use ($permittedFirmIds) {
            $q->whereIn('firms.id', $permittedFirmIds);
        })->orderBy('name')->get();
    }

    public function generateBills()
    {
        return $this->hasMany(GenerateBill::class);
    }

    public function generateChalans()
    {
        return $this->hasMany(GenerateChalan::class);
    }

    public function inputChalans()
    {
        return $this->hasMany(InputChalan::class);
    }

    public function outputChalans()
    {
        return $this->hasMany(OutputChalan::class);
    }

    public function purchaseBills()
    {
        return $this->hasMany(PurchaseBill::class);
    }

    public function rcvdPayments()
    {
        return $this->hasMany(RcvdPayment::class);
    }
}
