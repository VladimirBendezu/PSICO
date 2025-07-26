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
        Schema::create('personality_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relación con el usuario
            $table->integer('ap_1');
            $table->integer('ap_2');
            $table->integer('ap_3');
            $table->integer('ap_4');
            $table->integer('ap_5');
            $table->integer('re_1');
            $table->integer('re_2');
            $table->integer('re_3');
            $table->integer('re_4');
            $table->integer('re_5');
            $table->integer('ex_1');
            $table->integer('ex_2');
            $table->integer('ex_3');
            $table->integer('ex_4');
            $table->integer('ex_5');
            $table->integer('am_1');
            $table->integer('am_2');
            $table->integer('am_3');
            $table->integer('am_4');
            $table->integer('am_5');
            $table->integer('ne_1');
            $table->integer('ne_2');
            $table->integer('ne_3');
            $table->integer('ne_4');
            $table->integer('ne_5');
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personality_tests');
    }
};
