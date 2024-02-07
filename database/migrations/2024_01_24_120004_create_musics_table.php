<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('musics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger ('alb_id')->nullable();
            $table->unsignedBigInteger ('art_id');
            $table->string('name');
            $table->date('release_date')->nullable();
            $table->string('rink')->nullable();
            $table->unsignedBigInteger ('aff_id')->nullable();
            $table->timestamps();
    
            // 外部キー制約の追加なども可能
            //$table->foreign('alb_id')->references('id')->on('albums');
            $table->foreign('art_id')->references('id')->on('artists');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('musics');
    }
};
