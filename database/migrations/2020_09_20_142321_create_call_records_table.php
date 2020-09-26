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
            $table->id();
            $table->string('from_number',15)->nullable(false);
            $table->string('to_number',15)->nullable(false);
            $table->string('call_duration')->nullable(false);
            $table->enum('call_status',['failed', 'completed','busy','no_answer']);
            $table->enum('call_directions',['incoming', 'outcoming']);
            $table->string('call_recording_link');
            $table->string('branch_id');
            $table->integer('group1');
            $table->integer('group2');
            $table->integer('group3');
            $table->integer('group4');
            $table->timestamps();
            $table->softDeletes();
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
