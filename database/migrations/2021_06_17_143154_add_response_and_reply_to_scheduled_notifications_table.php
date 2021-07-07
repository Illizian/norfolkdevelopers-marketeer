<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddResponseAndReplyToScheduledNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scheduled_notifications', function (Blueprint $table) {
            $table->json('response')->nullable()->after('sent');

            $table->foreignId('reply_id')->nullable();
            $table->foreign('reply_id')
                ->references('id')
                ->on('scheduled_notifications')
                ->onDelete('SET NULL');
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
            $table->dropForeign(['reply_id']);
            $table->dropColumn(
                'response',
                'reply_id',
            );
        });
    }
}
