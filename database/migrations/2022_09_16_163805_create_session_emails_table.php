<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('session_emails', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('session_id')->constrained();
            $table->string('title');
            $table->string('body');
            $table->integer('type');
            $table->date('date');
            $table->time('time');
            $table->integer('status');
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
        Schema::dropIfExists('session_emails');
    }
}
