<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('availability_repeateds', function (Blueprint $table) {
            if (!Schema::hasColumn('availability_repeateds', 'time')) {
                $table->json('time')->after('day_of_week')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('availability_repeateds', function (Blueprint $table) {
            $table->dropColumn('time');
        });
    }
};
