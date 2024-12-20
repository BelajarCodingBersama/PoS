<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'slug'];

    /** Relationship */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function salary(): HasOne
    {
        return $this->hasOne(Salary::class);
    }
}
