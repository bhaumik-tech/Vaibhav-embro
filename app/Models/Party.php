<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    protected $fillable = ['name', 'address', 'gst_number', 'vatav', 'sgst', 'cgst', 'tds'];
}
