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
            $table->enum('status', ['scheduled', 'confirmed', 'completed','notation', 'pending', 'cancelled'])->default('scheduled');

            $table->boolean('canceled_by_monitor')->nullable();
            $table->text('reason')->nullable();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->integer('timing')->nullable();
        });
        Schema::table('instructor_profiles', function (Blueprint $table) {
            $table->float('hourPrice')->default(15);
            $table->float('hourDiscount')->default(4);
            $table->float('tva')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {            
            $table->dropColumn('canceled_by_monitor');
            $table->dropColumn('canceled_by_monitor');
            $table->dropColumn('reason');
        });

        Schema::table('users', function (Blueprint $table) {            
            $table->dropColumn('timing');
        });

        Schema::table('instructor_profiles', function (Blueprint $table) {
            $table->dropColumn('hourPrice');
            $table->dropColumn('hourDiscount');
            $table->dropColumn('tva');
        });
    }
};
