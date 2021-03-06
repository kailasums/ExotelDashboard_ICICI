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
            $table->string('phone_number',15)->unique();
            $table->enum('is_admin',['YES','NO'])->default('NO');
            $table->enum('can_make_call',['YES','NO'])->default('NO');
            $table->enum('portal_access',['YES','NO'])->default('NO');
            $table->enum('level',['LEVEL0','LEVEL1','LEVEL2','LEVEL3','LEVEL4'])->default('level0');
            $table->integer('group1');
            $table->integer('group2');
            $table->integer('group3');
            $table->integer('group4');
            $table->string('designation');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['email', 'phone_number']);
            $table->index(['group1', 'group2','group3', 'group4']);
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
