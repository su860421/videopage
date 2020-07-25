<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUservideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uservideos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email');
            $table->string('title');
            $table->string('msg');
            $table->string('videokey')->unique();
            $table->string('imgkey')->unique();
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
        Schema::dropIfExists('uservideos');
    }
}
