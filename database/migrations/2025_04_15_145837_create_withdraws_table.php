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
        Schema::create('withdraws', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monitor_id')->constrained('users')->onDelete('cascade');
            $table->integer('ammount');
            $table->integer('duration');
            $table->boolean('payed')->default(false);
            $table->string('currency');
            $table->string('invoice_code')->nullable();
	        $table->string('invoice_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdraws');
    }
};
