<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Admin',
                'slug' => Str::slug('admin')
            ],
            [
                'name' => 'Cashier',
                'slug' => Str::slug('cashier')
            ],
            [
                'name' => 'Finance',
                'slug' => Str::slug('finance')
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
