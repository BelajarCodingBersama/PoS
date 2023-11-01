<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnitType extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /** Relationships */
    public function payrollSettings(): HasMany
    {
        return $this->hasMany(PayrollSetting::class);
    }
}
