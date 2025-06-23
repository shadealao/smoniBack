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
        Schema::table('instructor_profiles', function (Blueprint $table) {
            $table->string('juridic_form')->nullable();
            $table->string('siret')->nullable();
            $table->string('num_activity')->nullable();
            $table->string('num_tva')->nullable();
            $table->string('num_teach_authorization')->nullable();
            $table->timestamp('date_teach_permit')->nullable();
            $table->timestamp('date_medical_visit')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('instructor_profiles', function (Blueprint $table) {
            $table->dropColumn('juridic_form');
            $table->dropColumn('siret');
            $table->dropColumn('num_activity');
            $table->dropColumn('num_tva');
            $table->dropColumn('num_teach_authorization');
            $table->dropColumn('date_teach_permit');
            $table->dropColumn('date_medical_visit');
        });
    }
};
