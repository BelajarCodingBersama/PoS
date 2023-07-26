<?php

namespace Database\Seeders;

use App\Models\PayrollSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PayrollSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $payrollSettings = [
            [
                'name' => 'allowances',
                'nominal' => 5,
                'unit_type_id' => 2
            ],
            [
                'name' => 'tax',
                'nominal' => 10,
                'unit_type_id' => 2
            ]
        ];

        foreach ($payrollSettings as $payrollSetting) {
            PayrollSetting::create($payrollSetting);
        }
    }
}
