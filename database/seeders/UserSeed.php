<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'abiodun samuel',
            'email' => 'abiodun@gmail.com',
            'password' => Hash::make('Password@123'),
            'roles' => 'admin'
        ]);
    }
}
