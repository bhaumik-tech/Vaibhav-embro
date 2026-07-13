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
}
