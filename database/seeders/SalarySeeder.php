<?php

namespace Database\Seeders;

use App\Models\Salary;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $salaries = [
            [
                'role_id' => 1,
                'nominal' => rand(3000000, 7000000)
            ],
            [
                'role_id' => 2,
                'nominal' => rand(3000000, 7000000)
            ],
            [
                'role_id' => 3,
                'nominal' => rand(3000000, 7000000)
            ],
        ];

        foreach ($salaries as $salary) {
            Salary::create($salary);
        }
    }
}
