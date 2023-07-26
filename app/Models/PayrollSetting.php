<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollSetting extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'nominal', 'unit_type_id'];

    /** Relationship */
    public function unitType()
    {
        return $this->belongsTo(UnitType::class);
    }
}
