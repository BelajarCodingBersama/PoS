<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    /** Relationships */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Acessors */
    protected function formatPaymentDate(): Attribute
    {
        return new Attribute(
            get: function () {
                if (empty($this->payment_date)) {
                    return null;
                }

                return Carbon::parse($this->payment_date)->format('d-m-Y');
            }
        );
    }

    protected function formatBasicSalary(): Attribute
    {
        return new Attribute(
            get: fn () => number_format($this->basic_salary, 0, ",", ".")
        );
    }

    protected function formatAllowances(): Attribute
    {
        return new Attribute(
            get: fn () => number_format($this->allowances, 0, ",", ".")
        );
    }

    protected function formatTax(): Attribute
    {
        return new Attribute(
            get: fn () => number_format($this->tax, 0, ",", ".")
        );
    }

    protected function formatNetPay(): Attribute
    {
        return new Attribute(
            get: fn () => number_format($this->net_pay, 0, ",", ".")
        );
    }
}
