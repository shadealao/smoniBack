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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learner_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('plan_id')->constrained('services')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('type_service', ['on_site', 'online', 'hybrid'])->default('online'); // à adapter selon les types prévus
            $table->enum('status', ['active', 'expired', 'cancelled'])->default('active');
            $table->boolean('auto_renewal')->default(false);
            $table->foreignId('payment_id')->nullable()->constrained('payments');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
