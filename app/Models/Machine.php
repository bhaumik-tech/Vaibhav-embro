<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'bonus_production_enabled' => 'boolean',
        'bonus_frame_enabled' => 'boolean',
    ];

    public function firm()
    {
        return $this->belongsTo(Firm::class);
    }

    public function latestProgram()
    {
        return $this->hasOne(Program::class)
                    ->where('is_live', 1)
                    ->where('process', '!=', 'R.D')
                    ->latest('id');
    }
}
