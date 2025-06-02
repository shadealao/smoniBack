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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learner_id')->constrained('users');
            $table->foreignId('instructor_id')->constrained('users');
            $table->foreignId('availability_id')->constrained('availabilities')->nullable();
            $table->foreignId('vehicle_id')->constrained('vehicles');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('duration');
            $table->enum('status', ['scheduled', 'confirmed', 'completed', 'cancelled']);
            $table->text('cancellation_reason')->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->json('lesson_notes')->nullable();
            $table->boolean('presence_student')->default(false);
            $table->boolean('presence_monitor')->default(false);
            $table->boolean('finished')->default(false);
            $table->string('tag')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
