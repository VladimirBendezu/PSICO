<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestsTable extends Migration
{
    public function up()
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // FK a usuarios
            $table->string('full_name');
            $table->integer('age');
            $table->tinyInteger('p1');
            $table->tinyInteger('p2');
            $table->tinyInteger('p3');
            $table->tinyInteger('p4');
            $table->tinyInteger('p5');
            $table->tinyInteger('p6');
            $table->tinyInteger('p7');
            $table->tinyInteger('p8');
            $table->tinyInteger('p9');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tests');
    }
}
