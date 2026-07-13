<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karigar extends Model
{
    protected $fillable = [
        'name',
        'dob',
        'aadhar_no',
        'mobile_no',
        'aadhar_front',
        'aadhar_back',
        'photo',
        'bank_name',
        'bank_account_no',
        'machine_1_id',
        'machine_1_top_rs',
        'machine_1_dup_rs',
        'machine_2_id',
        'machine_2_top_rs',
        'machine_2_dup_rs',
        'machine_3_id',
        'machine_3_top_rs',
        'machine_3_dup_rs',
    ];

    public function machine1()
    {
        return $this->belongsTo(Machine::class, 'machine_1_id');
    }

    public function machine2()
    {
        return $this->belongsTo(Machine::class, 'machine_2_id');
    }

    public function machine3()
    {
        return $this->belongsTo(Machine::class, 'machine_3_id');
    }
}
