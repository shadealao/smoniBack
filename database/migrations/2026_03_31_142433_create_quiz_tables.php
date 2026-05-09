<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Quiz categories table
        Schema::create('quiz_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique(); // VE, VI, PS
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('pass_score')->default(15); // Minimum score to pass (out of 20)
            $table->timestamps();
        });

        // Quiz questions table
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('quiz_categories')->onDelete('cascade');
            $table->integer('question_number'); // Original question number from PDF
            $table->text('practical_action'); // "Montrez...", "Vérifiez..."
            $table->text('theoretical_question'); // Follow-up question
            $table->text('correct_answer');
            $table->json('options')->nullable(); // For multiple choice if needed
            $table->timestamps();
        });

        // User quiz attempts table
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained('quiz_categories')->onDelete('cascade');
            $table->integer('score')->default(0);
            $table->integer('total_questions')->default(20);
            $table->boolean('passed')->default(false);
            $table->timestamp('started_at');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        // User quiz answers table
        Schema::create('quiz_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('quiz_attempts')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('quiz_questions')->onDelete('cascade');
            $table->text('user_answer');
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_answers');
        Schema::dropIfExists('quiz_attempts');
        Schema::dropIfExists('quiz_questions');
        Schema::dropIfExists('quiz_categories');
    }
};
