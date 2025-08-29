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
        Schema::table('learner_profiles', function (Blueprint $table) {
            $table->text('identity')->nullable();
            $table->text('accommodation')->nullable();
            $table->text('authorize')->nullable();
            $table->text('identityPhoto')->nullable();
            $table->text('assr')->nullable();
            $table->text('cip')->nullable();
            $table->text('snu')->nullable();
            $table->text('medicalVisit')->nullable();
            $table->text('neph')->nullable();
        });

        //  Schema::table('instructor_profiles', function (Blueprint $table) {
        //     $table->text('grayCard')->nullable();
        //     $table->text('greenCard')->nullable();
        //     $table->text('assurance')->nullable();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('learner_profiles', function (Blueprint $table) {            
            $table->dropColumn('identity');
            $table->dropColumn('accommodation');
            $table->dropColumn('authorize');
            $table->dropColumn('identityPhoto');
            $table->dropColumn('assr');
            $table->dropColumn('cip');
            $table->dropColumn('snu');
            $table->dropColumn('medicalVisit');
            $table->dropColumn('neph');
        });

        // Schema::table('instructor_profiles', function (Blueprint $table) {
        //     $table->dropColumn('grayCard');
        //     $table->dropColumn('greenCard');
        //     $table->dropColumn('assurance');
        // });
    }
};
