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
        Schema::create('module_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('training_modules')->onDelete('cascade');
            $table->string('code')->nullable(); // Ex: "C1a", "C1b"
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('details')->nullable();
            $table->integer('duration_minutes')->default(0);
            $table->string('step_type')->nullable();
            $table->enum('status', ['not_started', 'in_progress', 'completed'])->default('not_started');
            $table->integer('display_order')->default(0);
            $table->boolean('required_for_completion')->default(false);
            $table->json('validation_criteria')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_steps');
    }
};
