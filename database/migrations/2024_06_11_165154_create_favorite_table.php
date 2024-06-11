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
        Schema::create('favorite', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger ('user_id');
            $table->tinyInteger('category')->comment('0:曲,1:ｱｰﾃｨｽﾄ,2:ｱﾙﾊﾞﾑ,3:ﾌﾟﾚｲﾘｽﾄ');
            $table->unsignedBigInteger ('detail_id')->comment('詳細データid');
            $table->timestamps();
            
            // 外部キー制約
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorite');
    }
};
