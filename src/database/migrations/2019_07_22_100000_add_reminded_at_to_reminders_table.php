<?php

use Illuminate\Database\Schema\Blueprint;
use LaravelEnso\Migrator\app\Database\Migration;

class AddRemindedAtToRemindersTable extends Migration
{
    public function up()
    {
        Schema::table('reminders', function (Blueprint $table) {
            $table->datetime('reminded_at')
                ->after('remind_at')
                ->nullable()
                ->index();
            $table->index('remind_at');
        });
    }

    public function down()
    {
        Schema::table('reminders', function (Blueprint $table) {
            $table->dropColumn('reminded_at');
            $table->dropIndex('remind_at');
        });
    }
}
