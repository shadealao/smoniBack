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
            $table->foreignId('learner_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('monitor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('step_item_id')->constrained('step_module_items')->unique()->onDelete('cascade');
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
