<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@dashcool.sch.id'],
            [
                'name' => 'Administrator Utama',
                'password' => Hash::make('password'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'spmb@dashcool.sch.id'],
            [
                'name' => 'Panitia SPMB',
                'password' => Hash::make('password'),
            ]
        );
    }
}
