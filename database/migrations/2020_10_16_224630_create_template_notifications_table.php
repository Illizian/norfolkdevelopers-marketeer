<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemplateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('template_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->string('destination')->nullable();
            $table->text('message');
            $table->text('modifier');
            $table->timestamps();

            $table->bigInteger('template_id')
                ->nullable()
                ->unsigned();
            $table->foreign('template_id')
                ->references('id')
                ->on('templates')
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
        Schema::dropIfExists('template_notifications');
    }
}
