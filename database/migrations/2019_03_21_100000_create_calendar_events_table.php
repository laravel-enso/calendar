<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('parent_id')->nullable()->index();

            $table->unsignedInteger('calendar_id')->index();
            $table->foreign('calendar_id')->index()
                ->references('id')->on('calendars')->onDelete('cascade');

            $table->string('title');
            $table->text('body')->nullable();
            $table->tinyInteger('frequency');

            $table->date('start_date')->index();
            $table->time('start_time');
            $table->date('end_date')->index();
            $table->time('end_time');

            $table->date('recurrence_ends_at')->nullable();

            $table->boolean('is_all_day');

            $table->string('location')->nullable();
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();

            $table->usignedInteger('created_by')->index()->nullable();
            $table->foreign('created_by')->references('id')->on('users');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('calendar_events');
    }
};
