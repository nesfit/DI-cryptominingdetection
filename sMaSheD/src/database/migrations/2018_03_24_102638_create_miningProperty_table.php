<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMiningPropertyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('miningProperties', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('server_id');
            $table->foreign('server_id')->references('id')->on('servers');
            $table->ipAddress('address');
            $table->string('protocol');
            $table->unsignedInteger('port');
            $table->boolean('status')->default(false);
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
        Schema::dropIfExists('miningProperties');
    }
}
