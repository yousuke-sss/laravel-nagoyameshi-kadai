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
        Schema::create('restaurant_user', function (Blueprint $table) {
            $table->id();  // 自動生成のID

            // 外部キー: restaurant_id
            $table->foreignId('restaurant_id')
                ->constrained()  // デフォルトで 'restaurants' テーブルを参照
                ->cascadeOnDelete();  // 参照先削除時に予約も削除

            // 外部キー: user_id
            $table->foreignId('user_id')
                ->constrained()  // デフォルトで 'users' テーブルを参照
                ->cascadeOnDelete();  // 参照先削除時に予約も削除

            $table->timestamps();  // created_at と updated_at を自動生成
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_user');
    }
};
