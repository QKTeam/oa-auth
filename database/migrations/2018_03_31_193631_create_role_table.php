<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('short_name')->unique();
            $table->string('icon');
            $table->string('color');
            $table->timestamps();
        });
        DB::table('role')->insert([
            [
                'id' => 1,
                'name' => '负责人',
                'short_name' => 'director',
                'color' => '#ff5500',
                'icon' => 'rocket',
            ],
            [
                'id' => 2,
                'name' => '大佬',
                'short_name' => 'core',
                'color' => '#108ee9',
                'icon' => 'sun',
            ],
            [
                'id' => 3,
                'name' => '成员',
                'short_name' => 'member',
                'color' => '#2db7f5',
                'icon' => 'lightbulb',
            ],
            [
                'id' => 4,
                'name' => '萌新',
                'short_name' => 'trainee',
                'color' => '#87d068',
                'icon' => 'user',
            ],
            [
                'id' => 5,
                'name' => '曾经',
                'short_name' => 'was',
                'color' => '#ffc53d',
                'icon' => 'lock',
            ],
            [
                'id' => 6,
                'name' => '指导老师',
                'short_name' => 'tutor',
                'color' => '#eb2f96',
                'icon' => 'heart',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role');
    }
}
