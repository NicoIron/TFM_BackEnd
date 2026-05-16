<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proyecto_usuarios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('id_proyecto', 50);
            $table->string('id_usuario', 50);
            $table->string('id_organizacion', 50);
            $table->foreign('id_proyecto')
                ->references('id_proyecto')->on('proyectos')
                ->onDelete('restrict');
            $table->foreign('id_usuario')
                ->references('id_usuario')->on('usuarios')
                ->onDelete('restrict');
            $table->foreign('id_organizacion')
                ->references('id_organizacion')->on('organizacion')
                ->onDelete('restrict');
            $table->unique(['id_proyecto', 'id_usuario']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proyecto_usuarios');
    }
};
