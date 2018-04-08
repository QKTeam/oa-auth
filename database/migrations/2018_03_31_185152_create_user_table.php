<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('email')->unique();
            $table->string('remember_token')->nullable();
            $table->integer('role_id');
            $table->string('phone')->nullable();
            $table->string('name')->comment('real name');
            $table->boolean('gender')->default(1)->comment('1 for male, 0 for female');
            $table->softDeletes();
            $table->timestamps();
        });
        DB::table('user')->insert([
            'username' => 'administrator',
            'name' => '管理员',
            'password' => bcrypt('d033e22ae348aeb5660fc2140aec35850c4da997'),
            'email' => 'admin@qkteam.com',
            'role_id' => 1,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
}
