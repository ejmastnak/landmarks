<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
 * Run the migrations.
 *
 * @return void
 */
    public function up()
    {
        Schema::create('landmarks', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->unsignedBigInteger('landmark_type_id');
            $table->foreign('landmark_type_id')->references('id')->on('landmark_types')->cascadeOnDelete();
            $table->string('city', 200);
            $table->unsignedBigInteger('country_id');
            $table->foreign('country_id')->references('id')->on('countries')->cascadeOnDelete();
            $table->text('comment')->nullable();
            $table->string('link', 200)->nullable();
            $table->timestamps();
        });
    }

    /**
 * Reverse the migrations.
 *
 * @return void
 */
    public function down()
    {
        Schema::dropIfExists('landmarks');
    }
};
