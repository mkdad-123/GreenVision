<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'name'     => 'Super Admin',
            'email'    => 'ad@example.com',
            'password' => bcrypt('123456'),
        ]);
    }
}
