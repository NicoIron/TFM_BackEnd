<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('jerarquia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_organizacion')->constrained('organizacion');
            $table->string('cargo', 100);
            $table->boolean('eliminado')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jerarquia');
    }
};
