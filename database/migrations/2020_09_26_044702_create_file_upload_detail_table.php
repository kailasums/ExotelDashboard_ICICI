<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileUploadDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_upload_details', function (Blueprint $table) {
            $table->id();
            $table->string('file_name',255)->nullable(false);
            $table->enum('upload_status',['completed','completed-with-error','pending','processing', 'failed']);
            $table->string('remark',255)->nullable(true)->default("");
            $table->timestamps();
            $table->softDeletes();
            $table->index(['upload_status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
