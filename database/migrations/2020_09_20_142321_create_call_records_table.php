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
        Schema::create('call_logs', function (Blueprint $table) {
            $table->id();
            $table->string("call_sid",255);
            $table->string("agent_name",255);
            $table->string("agent_phone_number",15);
            $table->integer("user_id");
            $table->string('from_number',15)->nullable(false);
            $table->string('to_number',15)->nullable(false);
            $table->enum('call_direction',['Incoming', 'Outgoing']);
            $table->string('call_duration',15)->nullable(false)->default('0');
            $table->string('dial_call_duration',15)->nullable(false)->default('0');
            $table->timestamp('date_time')->nullable(false)->useCurrent();;
            $table->string('call_status',100); //,['Failed', 'Completed','Busy','No Answer']
            $table->string('call_recording_link');
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
        Schema::table('call_logs', function (Blueprint $table) {
            //
        });
    }
}
