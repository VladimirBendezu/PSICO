<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionsTable extends Migration
{
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->longText('payload');
            $table->unsignedInteger('last_activity');
            $table->unsignedBigInteger('user_id')->nullable(); 
            $table->string('ip_address')->nullable(); 
            $table->string('user_agent')->nullable(); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('sessions');
    }
}
