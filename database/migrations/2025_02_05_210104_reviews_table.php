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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id(); // IDカラム
            $table->text('content'); // レビュー内容
            $table->unsignedInteger('score'); // 符号なしのスコア（星の数）
            $table->foreignId('restaurant_id') // 店舗のID
                  ->constrained() // 外部キー制約
                  ->cascadeOnDelete(); // 参照先が削除されると同時に削除される

            $table->foreignId('user_id') // 会員のID
                  ->constrained() // 外部キー制約
                  ->cascadeOnDelete(); // 参照先が削除されると同時に削除される

            $table->timestamps(); // created_at と updated_at カラム
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
