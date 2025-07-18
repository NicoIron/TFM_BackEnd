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
 Schema::create('tickets_logs', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('id_ticket');
    $table->string('action', 255);
    $table->boolean('eliminado')->default(false);
    $table->timestamps();

    $table->foreign('id_ticket')->references('id')->on('tickets');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets_logs');
    }
};
