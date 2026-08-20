<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DropdownOption extends Model
{
    protected $fillable = ['column_name', 'value', 'parent_id'];

    public function children()
    {
        return $this->hasMany(DropdownOption::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(DropdownOption::class, 'parent_id');
    }
}
