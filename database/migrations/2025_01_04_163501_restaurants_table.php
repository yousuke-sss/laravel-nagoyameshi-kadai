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
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id(); // ID
            $table->string('name'); // 店舗名
            $table->string('image')->default(''); // 店舗画像（デフォルト値は''）
            $table->text('description'); // 説明
            $table->unsignedInteger('lowest_price'); // 最低価格（符号無し）
            $table->unsignedInteger('highest_price'); // 最高価格（符号無し）
            $table->string('postal_code'); // 郵便番号
            $table->string('address'); // 住所
            $table->time('opening_time'); // 開店時間
            $table->time('closing_time'); // 閉店時間
            $table->unsignedInteger('seating_capacity'); // 予約可能な座席数（符号無し）
            $table->timestamps(); // 作成日時と更新日時
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
