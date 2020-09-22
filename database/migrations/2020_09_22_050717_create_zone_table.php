<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZoneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('zone_masters', function (Blueprint $table) {
            $table->increments('zone_id');
            $table->string('zone_name',255)->nullable(false);
            $table->unsignedBigInteger('mega_zone_id');
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
        Schema::table('zone_masters', function (Blueprint $table) {
            //
        });
    }
}
