<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->unsignedBigInteger('grade_level_id')->nullable();
            $table->unsignedBigInteger('section_id')->nullable();
            $table->unsignedBigInteger('school_year_id')->nullable();
            $table->string('days')->nullable()->default('None');
            $table->string('start_time')->nullable()->default('None');
            $table->string('end_time')->nullable()->default('None');
            $table->timestamps();

            $table->foreign('subject_id')->references('id')->on('subjects');
            $table->foreign('teacher_id')->references('id')->on('users');
            $table->foreign('grade_level_id')->references('id')->on('grade_levels');
            $table->foreign('section_id')->references('id')->on('sections');
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
        Schema::dropIfExists('classes');
    }
}
