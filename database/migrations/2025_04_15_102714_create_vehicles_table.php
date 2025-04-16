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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained('users')->onDelete('cascade');
            $table->string('brand');
            $table->string('model');
            $table->integer('year')->nullable();
            $table->string('plate_number')->unique();
            $table->enum('fuel_type', ['essence', 'diesel', 'électrique', 'hybride']);
            $table->date('insurance_expiry')->nullable();
            $table->date('technical_inspection_date')->nullable();
            $table->string('photo_url')->nullable();
            $table->string('color')->nullable();
            $table->enum('gearbox_type', ['manual', 'automatic']);
            $table->enum('status', ['available', 'maintenance', 'out_of_service'])->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
