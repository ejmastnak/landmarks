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
        Schema::create('landmark_sources', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->unsignedBigInteger('landmark_id');
            $table->foreign('landmark_id')->references('id')->on('landmarks')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('landmark_sources');
    }
};
