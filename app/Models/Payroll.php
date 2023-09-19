<?php

namespace App\Models;

use Carbon\Carbon;
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

    /** Acessor */
    public function getFormatPaymentDateAttribute()
    {
        if (empty($this->payment_date)) {
            return null;
        }
        return Carbon::parse($this->payment_date)->format('d-m-Y');
    }

    public function getFormatBasicSalaryAttribute()
    {
        return number_format($this->basic_salary, 0, ",", ".");
    }

    public function getFormatAllowancesAttribute()
    {
        return number_format($this->allowances, 0, ",", ".");
    }

    public function getFormatTaxAttribute()
    {
        return number_format($this->tax, 0, ",", ".");
    }

    public function getFormatNetPayAttribute()
    {
        return number_format($this->net_pay, 0, ",", ".");
    }
}
