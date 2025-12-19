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
            // Status order
            $table->enum('status', [
                'pending',
                'confirmed', 
                'processing',
                'production',
                'shipping',
                'completed',
                'cancelled'
            ])->default('pending')->after('is_displayed');
            
            // Quantity dan harga
            $table->integer('quantity')->default(1)->after('product_id');
            $table->decimal('price', 12, 2)->default(0)->after('quantity');
            $table->decimal('total_amount', 12, 2)->default(0)->after('amount');
            
            // Alamat lengkap
            $table->text('customer_address')->nullable()->after('customer_phone');
            
            // Catatan
            $table->text('notes')->nullable()->after('customer_address');
            
            // Relasi ke batch
            $table->foreignId('batch_id')->nullable()->after('product_id')
                  ->constrained()->onDelete('set null');
            
            // Index
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['batch_id']);
            $table->dropColumn([
                'status',
                'quantity',
                'price', 
                'total_amount',
                'customer_address',
                'notes',
                'batch_id'
            ]);
        });
    }
};
