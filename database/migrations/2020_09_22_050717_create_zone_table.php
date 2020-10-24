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
        Schema::create('zone_master', function (Blueprint $table) {
            $table->id('id');
            $table->string('zone_name',255)->nullable(false);
            $table->integer('megazone_id');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['megazone_id']);
            $table->index(['zone_name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('zone_master', function (Blueprint $table) {
            //
        });
    }
}
