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
        Schema::create('favorite_alb', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger ('user_id');
            $table->unsignedBigInteger ('fav_id')->comment('album_id');
            $table->timestamps();
            
            // 外部キー制約
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('fav_id')->references('id')->on('albums')->onDelete('cascade');
            
            // インデックスの追加
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorite_alb');
    }
};
