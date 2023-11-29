<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'user' => 'user1',
            'name' => 'user1',
            'phone' => '123456789',
            'password' => Hash::make('12345678'),
            'consent_Id1' => 1,
            'consent_Id2' => 1,
            'consent_Id3' => 0,
        ]);
    }
}
