<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentInboxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_inboxes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->longText('message');
            $table->boolean('Seen')->default(false);
            $table->foreignId('student_id')->constrained();
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
        Schema::dropIfExists('student_inboxes');
    }
}
