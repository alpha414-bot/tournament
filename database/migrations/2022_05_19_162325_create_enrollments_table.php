<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnrollmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('section_id');
            $table->foreignId('grade_level_id');
            $table->unsignedBigInteger('school_year_id');
            $table->string('status')->nullable();
            $table->timestamp('date_enrolled')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('section_id')->references('id')->on('sections');
            $table->foreign('grade_level_id')->references('id')->on('grade_levels');
            $table->foreign('school_year_id')->references('id')->on('school_years');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enrollments');
    }
}
