<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email',255)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone_number',15)->umique();
            $table->enum('is_admin',['YES','NO'])->default('NO');
            $table->enum('is_callable',['YES','NO'])->default('NO');
            $table->enum('level',['level0','level1','level2','level3','level4','level5'])->default('level0');
            $table->integer('group1');
            $table->integer('group2');
            $table->integer('group3');
            $table->integer('group4');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
