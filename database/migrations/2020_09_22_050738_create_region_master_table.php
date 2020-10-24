<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegionMasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('region_master', function (Blueprint $table) {
            $table->id();
            $table->string('region_name',255)->nullable(false);
            $table->integer('zone_id');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['region_name']);
            $table->index(['zone_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('region_master', function (Blueprint $table) {
            //
        });
    }
}
