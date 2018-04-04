<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('link', function (Blueprint $table) {
            $table->increments('id');
            $table->string('link_name')->unique();
            $table->string('link_url');
            $table->softDeletes();
            $table->timestamps();
        });
        DB::table('link')->insert([
            [
                'link_name' => 'portal',
                'link_url' => env('APP_URL'),
            ],
            [
                'link_name' => 'portal_admin',
                'link_url' => env('APP_URL') . '/admin',
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
        Schema::dropIfExists('link');
    }
}
