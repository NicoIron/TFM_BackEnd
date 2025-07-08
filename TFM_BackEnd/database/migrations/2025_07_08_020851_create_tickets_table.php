<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
     Schema::create('tickets', function (Blueprint $table) {
            $table->id(); // Clave primaria autoincremental
            $table->unsignedBigInteger('id_organizacion');
            $table->string('id_ticket', 50)->unique(); // ID de control externo
            $table->unsignedBigInteger('id_rol');
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_tipo_producto');
            $table->integer('monto');
            $table->string('proyecto', 100);
            $table->string('desc_compra', 255);
            $table->string('gestor', 100);
            $table->boolean('estado_solicitud')->default(false);
            $table->dateTime('fecha_limite');

            // Ya no es PRIMARY ni autoIncrement
            $table->bigInteger('num_ticket')->nullable()->unique();

            $table->boolean('eliminado')->default(false);
            $table->timestamps();

            // Foreign keys
            $table->foreign('id_organizacion')->references('id')->on('organizacion');
            $table->foreign('id_rol')->references('id')->on('roles');
            $table->foreign('id_usuario')->references('id')->on('usuarios');
            $table->foreign('id_tipo_producto')->references('id')->on('tipos_producto');

            // Restricción compuesta para control de duplicados
            $table->unique(['id_usuario', 'id_tipo_producto', 'proyecto', 'desc_compra'], 'idx_ticket_control');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
