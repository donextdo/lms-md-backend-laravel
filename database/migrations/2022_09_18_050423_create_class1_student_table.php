<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClass1StudentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class1_student', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->boolean('status');
            $table->foreignId('student_id')->constrained();
            $table->foreignId('class1_id')->constrained();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('class1_student');
    }
}
