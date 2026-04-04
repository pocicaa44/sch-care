<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin SCH Care',
            'email' => 'admin@sch.care',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
    
    }
}
