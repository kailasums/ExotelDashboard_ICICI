<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMegaZoneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('megazone_master', function (Blueprint $table) {
            $table->id();
            $table->string('megazone_name',255)->nullable(false);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['megazone_name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('megazone_master', function (Blueprint $table) {
            //
        });
    }
}
