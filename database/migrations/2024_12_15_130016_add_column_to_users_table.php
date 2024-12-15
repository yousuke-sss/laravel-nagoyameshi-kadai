<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('kana'); // ユーザー名（フリガナ）
            $table->string('postal_code'); // 郵便番号
            $table->string('address'); // 住所
            $table->string('phone_number'); // 電話番号
            $table->date('birthday')->nullable(); // 生年月日（NULL許容）
            $table->string('occupation')->nullable(); // 職業（NULL許容）
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
