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
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('status')->checkIn(['scheduled', 'confirmed', 'completed', 'cancelled', 'notation', 'pending'])->change();
            $table->boolean('canceled_by_monitor')->nullable();
            $table->text('reason')->nullable();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->integer('timing')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {            
            $table->enum('status', ['scheduled', 'confirmed', 'completed', 'cancelled'])->change();
            $table->dropColumn('canceled_by_monitor');
            $table->dropColumn('reason');
        });

        Schema::table('users', function (Blueprint $table) {            
            $table->dropColumn('timing');
        });
    }
};
