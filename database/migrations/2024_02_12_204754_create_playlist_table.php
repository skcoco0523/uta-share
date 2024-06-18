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
        Schema::create('playlist', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger ('user_id');
            $table->string('name');
            $table->tinyInteger('admin_flag')->default(0);
            $table->timestamps();

            // 外部キー制約
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // インデックスの追加
            $table->index('user_id');
            $table->index('name');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('playlist');
    }
};
