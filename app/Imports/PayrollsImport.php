<?php

namespace App\Imports;

use App\Models\Payroll;
use App\Models\PayrollSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PayrollsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {   
        $month = Carbon::now('m');
        $payrollCheck = Payroll::where('user_id', $row['user_id'])
            ->whereMonth('created_at', $month)
            ->first();
            
        if (empty($payrollCheck)) {
            $user = User::findOrFail($row['user_id']);
            $role = $user->role->name;

            $allowances = PayrollSetting::where('name', 'allowances')->first();
            $allowancesType = $allowances->unitType->name;

            $tax = PayrollSetting::where('name', 'tax')->first();
            $taxType = $tax->unitType->name;

            $basic_salary = $user->role->salary->nominal;
            $nominalAllowance = 0;
            $nominalTax = 0;
            $paymentStatus = $row['status'];

            if ($allowancesType == 'percent') {
                $nominalAllowance = ($basic_salary * $allowances->nominal) / 100;
            } else if ($allowancesType == 'number') {
                $nominalAllowance = $allowances->nominal;
            }

            if ($taxType == 'percent') {
                $nominalTax = (($basic_salary + $nominalAllowance) * $tax->nominal) / 100;
            } else if ($taxType == 'number') {
                $nominalTax = $tax->nominal;
            }
            /** check status */
            if ($paymentStatus == Payroll::STATUS_PAID) {
                $paymentDate = $row['payment_date'];
                $date = Carbon::parse($paymentDate)->format('Y-m-d');
            } else {
                $date = null;
            }
            
            return new Payroll([
                'role' => $role,
                'basic_salary' => $basic_salary,
                'allowances' => $nominalAllowance,
                'tax' => $nominalTax,
                'payment_date' => $date,
                'net_pay' => ($basic_salary + $nominalAllowance) - $nominalTax,
                'status' => $row['status'],
                'user_id' => $row['user_id']
            ]);
        }
    }
}