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
        Schema::create('exam_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learner_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('monitor_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('registration_date');
            $table->enum('status', ['registered', 'passed', 'failed', 'absent']);
            $table->decimal('result_score', 5, 2)->nullable();
            $table->text('instructor_comments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_registrations');
    }
};
