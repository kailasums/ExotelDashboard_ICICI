<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_logs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email',255);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone_number',15);
            $table->enum('is_admin',['YES','NO'])->default('NO');
            $table->enum('can_make_call',['YES','NO'])->default('NO');
            $table->enum('portal_access',['YES','NO'])->default('NO');
            $table->enum('level',['LEVEL0','LEVEL1','LEVEL2','LEVEL3','LEVEL4'])->default('level0')->nullable();
            $table->string('group1')->nullable();
            $table->string('group2')->nullable();
            $table->string('group3')->nullable();
            $table->string('group4')->nullable();
            $table->string('designation');
            $table->string('remark')->nullable();
            $table->enum('status',['success','error'])->default('success');
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
        Schema::dropIfExists('users_log');
    }
}
