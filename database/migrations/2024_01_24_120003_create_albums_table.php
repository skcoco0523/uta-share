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
        Schema::create('albums', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger ('art_id');
            $table->date('release_date')->nullable();
            $table->unsignedBigInteger ('aff_id')->nullable();
            $table->timestamps();

            // 外部キー制約
            $table->foreign('art_id')->references('id')->on('artists')->onDelete('cascade');
            //$table->foreign('aff_id')->references('id')->on('affiliates')->onDelete('cascade');

            // インデックスの追加
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('albums');
    }
};
