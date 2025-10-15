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
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->string('id_notificacion', 50)->unique();
            $table->string('id_usuario', 50);
            $table->string('id_organizacion', 50);
            $table->string('tipo_notificacion', 50)->comment('ticket_asignado, ticket_aprobado, ticket_rechazado, ticket_escalado');
            $table->string('titulo', 255);
            $table->text('mensaje');
            $table->string('id_ticket', 50)->nullable();
            $table->tinyInteger('leida')->default(0)->comment('0=no leída, 1=leída');
            $table->dateTime('fecha_creacion')->useCurrent();
            $table->dateTime('fecha_lectura')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->onDelete('cascade');
            $table->foreign('id_organizacion')->references('id_organizacion')->on('organizacion')->onDelete('cascade');
            $table->foreign('id_ticket')->references('id_ticket')->on('tickets')->onDelete('set null');

            // Indexes
            $table->index(['id_usuario', 'leida']);
            $table->index('id_organizacion');
            $table->index('fecha_creacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
