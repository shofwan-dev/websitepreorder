<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_number')->unique();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('target_quantity');
            $table->integer('current_quantity')->default(0);
            $table->enum('status', [
                'planning', 
                'collecting', 
                'production', 
                'qc', 
                'packaging', 
                'shipping', 
                'completed',
                'cancelled'
            ])->default('planning');
            $table->date('production_start_date')->nullable();
            $table->date('estimated_completion_date')->nullable();
            $table->date('actual_completion_date')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};