<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jerarquia_roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_jerarquia');
            $table->unsignedBigInteger('id_rol');
            $table->unsignedBigInteger('id_rol_superior')->nullable();
            $table->foreign('id_jerarquia')
                ->references('id')->on('jerarquia_inicial')
                ->onDelete('restrict');
            $table->foreign('id_rol')
                ->references('id')->on('roles')
                ->onDelete('restrict');
            $table->foreign('id_rol_superior')
                ->references('id')->on('roles')
                ->onDelete('restrict');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jerarquia_roles');
    }
};
