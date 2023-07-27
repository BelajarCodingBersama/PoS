<?php

namespace App\Console\Commands;

use App\Models\Payroll;
use App\Models\PayrollSetting;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AddPayroll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payroll:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add payroll every month';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all();

        $allowances = PayrollSetting::where('name', 'allowances')->first();
        $allowancesType = $allowances->unitType->name;

        $tax = PayrollSetting::where('name', 'tax')->first();
        $taxType = $tax->unitType->name;

        /** Lopping users */
        foreach ($users as $user) {

            $salary = $user->role->salary->nominal;
            $nominalAllowances = 0;
            $nominalTax = 0;

            if ($allowancesType == 'percent') {
                $nominalAllowances = ($salary * $allowances->nominal) / 100;
            } else if ($allowancesType == 'number') {
                $nominalAllowances = $allowances->nominal;
            }

            if ($taxType == 'percent') {
                $nominalTax = (($salary + $nominalAllowances) * $tax->nominal) / 100;
            } else if ($taxType == 'number') {
                $nominalTax = $tax->nominal;
            }

            /** New payroll for user */
            $payroll = new Payroll();
            $data = [
                'role' => $user->role->name,
                'basic_salary' => $salary,
                'allowances' => $nominalAllowances,
                'tax' => $nominalTax,
                'net_pay' => ($salary + $nominalAllowances) - $nominalTax,
                'status' => Payroll::STATUS_PENDING,
                'user_id' => $user->id
            ];

            $payroll->fill($data)->save();
        }
    }
}
