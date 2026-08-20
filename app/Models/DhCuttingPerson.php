<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DhCuttingPerson extends Model
{
    protected $fillable = [
        'person_name',
        'lj_type',
        'person_code',
        'mobile_no',
        'aadhar_card_no',
        'dob',
        'second_mobile_no',
        'full_address',
        'remark_note',
    ];
}
