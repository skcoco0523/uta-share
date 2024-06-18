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
        Schema::create('playlistdetail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger ('pl_id');
            $table->unsignedBigInteger ('mus_id');
            $table->timestamps();

            // 外部キー制約
            $table->foreign('pl_id')->references('id')->on('playlist')->onDelete('cascade');
            
            // インデックスの追加
            $table->index('pl_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('playlistdetail');
    }
};
