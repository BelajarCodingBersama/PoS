<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payroll extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_PAID = 'Paid';
    const STATUS_PENDING = 'Pending';

    protected $fillable = [
        'role', 'basic_salary', 'allowances', 'tax',
        'payment_date', 'net_pay', 'status', 'user_id'
    ];

    /** Relationship */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
