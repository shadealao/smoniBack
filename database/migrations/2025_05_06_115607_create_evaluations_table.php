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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained();
            $table->foreignId('instructor_id')->constrained('users');
            
            // Attitude
            $table->boolean('attitude_control_priority')->default(false);
            $table->boolean('attitude_learning_desire')->default(false);
            
            // Habiletés
            $table->enum('installation', ['weak', 'satisfactory', 'good'])->nullable();
            $table->enum('start_stop', ['weak', 'satisfactory', 'good'])->nullable();
            $table->enum('steering_control', ['weak', 'satisfactory', 'good'])->nullable();
            
            // Compréhension et mémoire
            $table->enum('comprehension', ['weak', 'satisfactory', 'good'])->nullable();
            $table->enum('memory', ['weak', 'satisfactory', 'good'])->nullable();
            
            // Perception
            $table->enum('trajectory', ['weak', 'satisfactory', 'good'])->nullable();
            $table->enum('orientation', ['weak', 'satisfactory', 'good'])->nullable();
            $table->enum('observation', ['weak', 'satisfactory', 'good'])->nullable();
            $table->enum('gaze', ['weak', 'satisfactory', 'good'])->nullable();
            
            // Émotivité
            $table->enum('emotivity', ['weak', 'satisfactory', 'good'])->nullable();
            $table->enum('tension', ['weak', 'satisfactory', 'good'])->nullable();
            
            // Résultats
            $table->integer('partial_results')->nullable();
            $table->enum('final_result', ['positive', 'negative']);
            
            // Proposition
            $table->integer('theory_hours')->default(0);
            $table->integer('practice_hours')->default(0);
            $table->enum('gearbox_type', ['manual', 'automatic']);
            $table->boolean('proposal_accepted')->default(false);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
