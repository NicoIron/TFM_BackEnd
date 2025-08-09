<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('id_usuario', 50)->unique();
            $table->string('nombre', 100);
            $table->string('apellido', 100);
            $table->string('email', 150)->unique();
            $table->text('password_hash');
            $table->text('username')->unique();
            $table->unsignedBigInteger('id_rol');
            $table->string('id_organizacion', 50);
            $table->unsignedBigInteger('id_jerarquia');
            $table->foreign('id_rol')
                ->references('id')->on('roles')
                ->onDelete('restrict');
            $table->foreign('id_organizacion')
                ->references('id_organizacion')->on('organizacion')
                ->onDelete('restrict');
            $table->foreign('id_jerarquia')
                ->references('id')->on('jerarquia_inicial')
                ->onDelete('restrict');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
