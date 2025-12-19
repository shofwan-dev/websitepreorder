<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('production_timelines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('batch_number')->default(1);
            $table->enum('stage', [
                'po_open', 
                'waiting_quota', 
                'production', 
                'qc', 
                'packaging', 
                'shipping', 
                'delivered'
            ])->default('po_open');
            $table->integer('stage_progress')->default(0);
            $table->text('notes')->nullable();
            $table->integer('estimated_days')->nullable();
            $table->date('actual_start_date')->nullable();
            $table->date('actual_end_date')->nullable();
            $table->timestamp('notified_at')->nullable();
            $table->timestamps();
            
            $table->index(['product_id', 'batch_number']);
            $table->index('stage');
        });
    }

    public function down()
    {
        Schema::dropIfExists('production_timelines');
    }
};