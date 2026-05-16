<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('id_ticket', 50)->unique();
            $table->string('id_organizacion', 50);
            $table->string('id_usuario', 50);
            $table->string('id_aprobador', 50)->nullable();
            $table->string('id_proyecto', 50);
            $table->string('id_tipo_producto', 50);
            $table->decimal('monto', 15, 2)->nullable();
            $table->text('proyecto')->nullable();
            $table->text('descr_compra')->nullable();
            $table->string('estado_ticket', 50);
            $table->timestamp('fecha_cierre')->nullable();

            $table->foreign('id_usuario')
                ->references('id_usuario')->on('usuarios')
                ->onDelete('restrict');
            $table->foreign('id_aprobador')
                ->references('id_usuario')->on('usuarios')
                ->onDelete('restrict');
            $table->foreign('id_proyecto')
                ->references('id_proyecto')->on('proyectos')
                ->onDelete('restrict');
            $table->foreign('id_tipo_producto')
                ->references('id_producto')->on('tipo_productos')
                ->onDelete('restrict');
            $table->foreign('id_organizacion')
                ->references('id_organizacion')->on('organizacion')
                ->onDelete('restrict');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
