<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableCallRecordsChangeGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('call_records', function (Blueprint $table) {
            $table->integer('group1')->change();
            $table->integer('group2')->change();
            $table->integer('group3')->change();
            $table->integer('group4')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('call_records', function (Blueprint $table) {
            //
        });
    }
}
