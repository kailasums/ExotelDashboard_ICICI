<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCallRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('call_records', function (Blueprint $table) {
            $table->increments('callsid');
            $table->string('fromNumber',10)->nullable(false);
            $table->string('toNumber',10)->nullable(false);
            $table->integer('callduration')->nullable(false);
            $table->enum('callstatus',['failed', 'completed','busy','no_answer']);
            $table->string('callRecordingLink');
            $table->string('branchId');
            $table->integer('group1');
            $table->string('group2',255);
            $table->string('group3',255);
            $table->string('group4',255);
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
        Schema::table('call_records', function (Blueprint $table) {
            //
        });
    }
}
