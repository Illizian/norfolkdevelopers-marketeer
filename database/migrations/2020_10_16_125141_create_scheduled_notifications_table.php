<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScheduledNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scheduled_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->text('destination')->nullable();
            $table->text('message');
            $table->boolean('sent')->default(0);
            $table->timestamp('scheduled_at');
            $table->timestamps();

            $table->bigInteger('event_id')
                ->nullable()
                ->unsigned();
            $table->foreign('event_id')
                ->references('id')
                ->on('events')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scheduled_notifications');
    }
}
