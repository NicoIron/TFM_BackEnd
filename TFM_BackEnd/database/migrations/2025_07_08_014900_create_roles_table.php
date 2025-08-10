<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('id_rol', 50)->unique();
            $table->string('nombre_rol', 100);
            $table->integer('nivel')->nullable();
            $table->string('id_organizacion', 50);
            $table->foreign('id_organizacion')->references('id_organizacion')->on('organizacion');
            $table->unsignedBigInteger('id_jerarquia');
            $table->foreign('id_jerarquia')->references('id')->on('jerarquia_inicial');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('roles');
    }
};
