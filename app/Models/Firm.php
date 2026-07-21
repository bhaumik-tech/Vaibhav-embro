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

        if (app()->has('current_page_permission_key')) {
            return static::getPermittedForPage(app('current_page_permission_key'));
        }

        $permittedIds = $user->getPermittedFirmIds();
        return static::whereIn('id', $permittedIds)->orderBy('name')->get();
    }

    public static function getPermittedForPage($page, $action = 'view')
    {
        $user = auth()->user();
        if (!$user) {
            return collect();
        }
        
        if ($user->isAdmin()) {
            return static::orderBy('name')->get();
        }

        $allPermittedFirmIds = $user->getPermittedFirmIds();
        $permittedIds = [];

        foreach ($allPermittedFirmIds as $firmId) {
            if ($user->hasPagePermission($page, $action, $firmId)) {
                $permittedIds[] = $firmId;
            }
        }

        return static::whereIn('id', $permittedIds)->orderBy('name')->get();
    }

    public function machines()
    {
        return $this->hasMany(Machine::class);
    }

    public function parties()
    {
        return $this->belongsToMany(Party::class);
    }
}
