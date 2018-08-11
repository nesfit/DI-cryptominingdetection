<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('history', function (Blueprint $table)
            {
                $table->unsignedInteger('status')->change();
            }
        );

        Schema::table('history', function(Blueprint $table)
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
        //
        Schema::table('history', function(Blueprint $table)
        {
            $table->dropColumn('reason');
        });
    }
}
