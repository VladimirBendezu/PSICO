<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (!Schema::hasTable('citas')) {
            Schema::create('citas', function (Blueprint $table) {
                $table->id();
                $table->string('especialidad');
                $table->integer('edad');
                $table->string('genero');
                $table->string('ubicacion');
                $table->string('motivo');
                $table->integer('exp_prev');
                $table->integer('dur_cita');
                $table->date('fecha');
                $table->boolean('eventos_locales');
                $table->string('condiciones_climaticas');
                $table->integer('promociones');
                $table->integer('num_medicos');
                $table->integer('tiempo_espera');
                $table->string('demografia');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cita_r_s');
    }
};
