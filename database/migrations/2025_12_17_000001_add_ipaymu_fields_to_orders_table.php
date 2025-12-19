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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('ipaymu_transaction_id')->nullable();
            $table->string('ipaymu_payment_url')->nullable();
            $table->string('ipaymu_session_id')->nullable();
            $table->timestamp('payment_expired_at')->nullable();
            $table->timestamp('paid_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['ipaymu_transaction_id', 'ipaymu_payment_url', 'ipaymu_session_id', 'payment_expired_at', 'paid_at']);
        });
    }
};
