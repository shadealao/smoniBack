<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quiz_questions', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn(['practical_action', 'theoretical_question']);
            
            // Add new simplified column
            $table->text('question_text')->after('question_number');
        });
    }

    public function down(): void
    {
        Schema::table('quiz_questions', function (Blueprint $table) {
            // Restore old columns
            $table->text('practical_action')->after('question_number');
            $table->text('theoretical_question')->after('practical_action');
            
            // Drop new column
            $table->dropColumn('question_text');
        });
    }
};
