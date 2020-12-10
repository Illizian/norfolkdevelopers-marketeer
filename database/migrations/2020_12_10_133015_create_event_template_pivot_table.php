<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventTemplatePivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_template', function (Blueprint $table) {
            $table->primary(['event_id','template_id']);

            $table->bigInteger('event_id')->unsigned();
            $table->bigInteger('template_id')->unsigned();

            $table->timestamps();

            $table->foreign('event_id')
                ->references('id')
                ->on('events');
            $table->foreign('template_id')
                ->references('id')
                ->on('templates');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_template');
    }
}
