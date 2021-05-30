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
            $table->string('firstName');
            $table->string('middleName');
            $table->string('lastName');
            $table->string('userName')->unique();
            $table->string('thumbnail');
            $table->string('phone');
            $table->string('email');
            $table->string('address');
            $table->foreignId('state_id')->constrained();
            $table->date('bithDate');
            $table->string('gender');
            $table->string('password');
            $table->foreignId('role_id')->constrained();
            $table->boolean('activity')->default(1);
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
        Schema::dropIfExists('users');
    }
}
