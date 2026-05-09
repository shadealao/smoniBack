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
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('type_service')->nullable();
            $table->string('gearbox')->nullable();
            $table->integer('hour')->nullable()->default(null);
        });

        Schema::table('examens', function (Blueprint $table) {
            $table->datetime('datetime')->nullable();
        });

        Schema::table('services', function (Blueprint $table) {
            $table->integer('month')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {            
            $table->dropColumn('type_service');
            $table->dropColumn('gearbox');
            $table->dropColumn('hour');
        });

        Schema::table('examens', function (Blueprint $table) {            
            $table->dropColumn('datetime');
        });

        Schema::table('services', function (Blueprint $table) {            
            $table->dropColumn('month');
        });
    }
};
