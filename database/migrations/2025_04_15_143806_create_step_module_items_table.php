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
        Schema::create('step_module_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('step_id')->constrained('module_steps')->onDelete('cascade');
            $table->string('description');
            $table->integer('order')->default(0);
            $table->boolean('is_critical')->default(false); // Si l'item est critique pour la validation
            $table->string('validation_criteria')->nullable(); // Critères de validation spécifiques
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('step_module_items');
    }
};
