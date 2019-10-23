<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalendarEventsTable extends Migration
{
    public function up()
    {
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('calendar_id')->unsigned()->index();

            $table->string('title');
            $table->text('body')->nullable();
            $table->tinyInteger('frequence');

            $table->datetime('starts_at');
            $table->datetime('ends_at');
            $table->date('recurrence_ends_at')->nullable();

            $table->boolean('is_all_day');
            $table->boolean('is_readonly');

            $table->string('location')->nullable();
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();

            $table->integer('created_by')->unsigned()->index()->nullable();
            $table->foreign('created_by')->references('id')->on('users');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('events');
    }
}
