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
                'name' => 'A',
                'slug' => Str::slug('A')
            ],
            [
                'name' => 'B',
                'slug' => Str::slug('B')
            ],
            [
                'name' => 'C',
                'slug' => Str::slug('C')
            ]
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
