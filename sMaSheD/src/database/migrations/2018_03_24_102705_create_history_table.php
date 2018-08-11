<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('history');
        Schema::create('history', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('miningProp_id');
            $table->foreign('miningProp_id')->references('id')->on('miningProperties');
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
        Schema::dropIfExists('history');
    }
}
