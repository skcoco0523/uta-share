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
        Schema::create('recommenddetail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger ('recom_id');
            $table->unsignedBigInteger ('detail_id')->comment('詳細データid');
            $table->timestamps();

            // 外部キー制約
            $table->foreign('recom_id')->references('id')->on('recommend');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recommenddetail');
    }
};
