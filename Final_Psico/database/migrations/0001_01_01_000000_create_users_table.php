<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // ID del usuario
            $table->string('name'); // Nombre del usuario
            $table->string('email')->unique(); // Email único del usuario
            $table->timestamp('email_verified_at')->nullable(); // Verificación del email
            $table->string('password'); // Contraseña del usuario
            $table->rememberToken(); // Token de recordatorio
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('users'); // Eliminar la tabla si existe
    }
}
