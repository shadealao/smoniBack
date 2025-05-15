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
        Schema::create('learner_progres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learner_id')->constrained('users');
            $table->foreignId('module_id')->constrained('training_modules');
            $table->foreignId('current_step_id')->nullable()->constrained('module_steps');
            $table->foreignId('step_item_id')->constrained('step_module_items')->onDelete('cascade');
            $table->enum('status', ['not_started', 'in_progress', 'completed'])->default('not_started');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('monitor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('instructor_notes')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learner_progres');
    }
};
