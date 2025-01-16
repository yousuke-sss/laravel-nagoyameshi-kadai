<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class User_taro extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'id' => 1,
            'name' => '侍 太郎',
            'email' => 'taro.samurai@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
            'kana' => 'サムライ タロウ',
            'postal_code' => '765-4321',
            'address' => 'Osaka, Japan',
            'phone_number' => '08098765432',
            'birthday' => '1985-05-15',
            'occupation' => 'Designer',
        ]);
    }
}
