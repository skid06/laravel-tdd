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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('semester_id')->constrained();
            $table->foreignId('subject_id')->constrained();
            $table->foreignId('course_id')->nullable()->constrained();
            $table->foreignId('school_year_id')->constrained();
            $table->boolean('strict_to_course')->default(0);
            $table->integer('max_students');
            $table->string('status');
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
        Schema::dropIfExists('enrollments');
    }
};
