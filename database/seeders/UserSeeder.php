<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        collect([
            [
                'name' => 'Michael Watts',
                'email' => 'wattsdeveloping@gmail.com',
                'password' => bcrypt('password'),
            ],
        ])->each(function ($seed) {
            User::create($seed);
        });
    }
}
