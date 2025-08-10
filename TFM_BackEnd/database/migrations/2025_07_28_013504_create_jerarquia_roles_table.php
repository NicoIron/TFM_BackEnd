<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jerarquia_roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_jerarquia');
            $table->foreign('id_jerarquia')->references('id')->on('jerarquia_inicial');

            $table->unsignedBigInteger('id_rol');
            $table->foreign('id_rol')->references('id')->on('roles');

            $table->unsignedBigInteger('id_rol_superior')->nullable();
            $table->foreign('id_rol_superior')->references('id')->on('roles');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jerarquia_roles');
    }
};

