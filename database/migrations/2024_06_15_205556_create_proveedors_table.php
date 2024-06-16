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
        Schema::create('proveedors', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_completo', 100);
            $table->string('celular', 12);
            $table->string('email', 100);
            $table->string('direccion', 100);
            $table->string('ruc', 20)->nullable(); // Registro Único de Contribuyentes
            $table->string('contacto_principal', 50)->nullable(); // Persona de contacto principal
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proveedors');
    }
};
