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
        Schema::create('instructor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('specialty')->nullable();
            $table->text('bio')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->double('solde')->default(0);
            $table->string('certification_number')->nullable();
            $table->date('certification_issue_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructor_profiles');
    }
};
