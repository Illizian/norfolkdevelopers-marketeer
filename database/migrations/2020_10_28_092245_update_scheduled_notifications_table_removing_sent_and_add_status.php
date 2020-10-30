<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateScheduledNotificationsTableRemovingSentAndAddStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scheduled_notifications', function (Blueprint $table) {
            $table->enum('status', [
                'pending',
                'sending',
                'sent',
                'failed',
            ])
                ->after('sent')
                ->default('pending');
        });

        DB::update('update scheduled_notifications set status = "sent" where sent = 1');

        Schema::table('scheduled_notifications', function (Blueprint $table) {
            $table->dropColumn('sent');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scheduled_notifications', function (Blueprint $table) {
            $table->boolean('sent')->before('status')->default(0);
        });

        DB::update('update scheduled_notifications set sent = 1 where not status = "pending"');

        Schema::table('scheduled_notifications', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
