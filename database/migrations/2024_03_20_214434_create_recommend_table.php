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
        Schema::create('recommend', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger ('user_id');
            $table->string('name');
            $table->tinyInteger('category')->comment('0:曲,1:ｱｰﾃｨｽﾄ,2:ｱﾙﾊﾞﾑ,3:ﾌﾟﾚｲﾘｽﾄ');
            $table->boolean('disp_flag')->default(false)->comment('表示フラグ');
            $table->tinyInteger('sort_num')->comment('表示順');
            $table->timestamps();

            // 外部キー制約
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // インデックスの追加
            $table->index('user_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recommend');
    }
};
