<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('user_number')->unique()->nullable();
            $table->string('mobile')->nullable();
            $table->string('birth_date')->nullable();
            $table->string('birth_place')->nullable();
            $table->longText('address')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('contact_relationship')->nullable();
            $table->unsignedBigInteger('section_id')->nullable();
            $table->unsignedBigInteger('grade_level_id')->nullable();
            $table->string('role')->nullable();
            $table->string('admission_type')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('section_id')->references('id')->on('sections');
            $table->foreign('grade_level_id')->references('id')->on('grade_levels');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
