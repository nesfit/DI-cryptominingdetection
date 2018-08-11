<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMiningPropTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('miningProperties', function (Blueprint $table)
            {
                $table->unsignedInteger('status')->change();
            }
        );

        Schema::table('miningProperties', function(Blueprint $table)
        {
            $table->string('reason')->after('status');
            }
        );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('miningProperties', function(Blueprint $table)
        {
            $table->dropColumn('reason');
        });
    }
}
