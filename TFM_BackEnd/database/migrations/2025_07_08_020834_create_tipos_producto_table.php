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
        Schema::create('tipos_producto', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('id_organizacion');
    $table->string('nombre', 100);
    $table->text('descripcion')->nullable();
    $table->unsignedBigInteger('id_padre')->nullable();
    $table->boolean('eliminado')->default(false);
    $table->timestamps();

    $table->foreign('id_organizacion')->references('id')->on('organizacion');
    $table->foreign('id_padre')->references('id')->on('tipos_producto');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos_producto');
    }
};
