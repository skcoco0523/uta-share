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
        Schema::create('custom_categories_define', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('bit_num')->unique();
            $table->tinyInteger('sort_num');
            $table->boolean('disp_flag')->default(false)->comment('表示フラグ');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_categories_define');
    }
};
