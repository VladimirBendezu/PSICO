<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitasDisponiblesTable extends Migration
{
    public function up()
    {
        Schema::create('citas_disponibles', function (Blueprint $table) {
            $table->id();
            $table->string('especialidad');
            $table->time('hora');
            $table->string('lugar');
            $table->date('fecha');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('citas_disponibles');
    }
}
