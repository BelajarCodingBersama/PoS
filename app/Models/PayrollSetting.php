<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollSetting extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'nominal', 'unit_type_id'];

    /** Relationships */
    public function unitType(): BelongsTo
    {
        return $this->belongsTo(UnitType::class);
    }
}
