<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Firm extends Model
{
    protected $fillable = [
        'name',
        'address',
        'gst_number',
        'bank_account_number',
    ];

    public static function getPermitted()
    {
        $user = auth()->user();
        if (!$user) {
            return collect();
        }
        
        if ($user->isAdmin()) {
            return static::orderBy('name')->get();
        }

        $permittedIds = $user->getPermittedFirmIds();
        return static::whereIn('id', $permittedIds)->orderBy('name')->get();
    }

    public function machines()
    {
        return $this->hasMany(Machine::class);
    }
}
