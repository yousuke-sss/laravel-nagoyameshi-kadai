<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class my extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'id' => 119,
            'name' => '関根 陽輔',
            'email' => 'yo.sekine130702@outlook.jp',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
            'kana' => 'セキネ ヨウスケ',
            'postal_code' => '765-4444',
            'address' => 'Nigata, Japan',
            'phone_number' => '07098765432',
            'birthday' => '2001-05-15',
            'occupation' => 'Designer',
        ]);
    
    }
}
