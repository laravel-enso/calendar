<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemindersTable extends Migration
{
    public function up()
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('event_id')->unsigned()->index();
            $table->foreign('event_id')->references('id')->on('events');

            $table->integer('created_by')->unsigned()->index();
            $table->foreign('created_by')->references('id')->on('users');

            $table->datetime('remind_at');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reminders');
    }
}
