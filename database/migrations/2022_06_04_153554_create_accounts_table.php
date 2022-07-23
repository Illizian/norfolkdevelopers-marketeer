<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('type');
            $table->enum('status', [
                'PENDING',
                'ENABLED',
                'DISABLED',
                'FAILED',
            ]);
            $table->string('profile_name')->nullable();
            $table->string('token')->nullable();
            $table->string('secret')->nullable();
            $table->string('refresh')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
};
