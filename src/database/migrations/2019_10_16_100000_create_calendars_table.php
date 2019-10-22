<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalendarsTable extends Migration
{
    public function up()
    {
        Schema::create('calendars', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('color');

            $table->integer('created_by')->unsigned()->index()->nullable();
            $table->foreign('created_by')->references('id')->on('users');

            $table->timestamps();
        });
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'calendar_id')) {
                $table->foreign('calendar_id')->references('id')->on('calendars')
                    ->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::dropIfExists('events');

        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex('calendar_id');
        });
    }
}
