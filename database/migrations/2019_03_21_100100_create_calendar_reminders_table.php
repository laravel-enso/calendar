<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('calendar_reminders', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('event_id')->index();
            $table->foreign('event_id')->references('id')->on('calendar_events')
                ->onDelete('cascade');

            $table->unsignedInteger('created_by')->index();
            $table->foreign('created_by')->references('id')->on('users');

            $table->datetime('scheduled_at')->index();
            $table->datetime('sent_at')->nullable()->index();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('calendar_reminders');
    }
};
