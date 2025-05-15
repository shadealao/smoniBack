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
        Schema::create('driving_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained();
            $table->enum('license_type', ['B1_AM', 'A1', 'A2', 'A'])->nullable();
            $table->enum('driving_experience', ['never', 'less_than_5h', 'more_than_5h'])->nullable();
            $table->enum('accompanied_by', ['friends', 'parents', 'driving_school'])->nullable();
            $table->enum('driving_location', ['city', 'road', 'path', 'parking'])->nullable();
            $table->enum('other_vehicle', ['bike', 'quad', 'motorcycle', 'other'])->nullable();
            $table->date('experience_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driving_experiences');
    }
};
