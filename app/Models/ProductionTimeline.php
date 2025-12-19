<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionTimeline extends Model
{
    protected $fillable = [
        'product_id',
        'batch_number',
        'stage',
        'stage_progress',
        'notes',
        'estimated_days',
        'actual_start_date',
        'actual_end_date',
        'notified_at'
    ];
    
    protected $casts = [
        'actual_start_date' => 'date',
        'actual_end_date' => 'date',
        'notified_at' => 'datetime'
    ];
    
    /**
     * Relationship dengan Product
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    
    /**
     * Get stage label
     */
    public function getStageLabelAttribute(): string
    {
        $labels = [
            'po_open' => 'PO Dibuka',
            'waiting_quota' => 'Menunggu Kuota',
            'production' => 'Produksi',
            'qc' => 'Quality Control',
            'packaging' => 'Pengemasan',
            'shipping' => 'Pengiriman',
            'delivered' => 'Terkirim'
        ];
        
        return $labels[$this->stage] ?? $this->stage;
    }
    
    /**
     * Get stage color
     */
    public function getStageColorAttribute(): string
    {
        $colors = [
            'po_open' => 'bg-blue-100 text-blue-800',
            'waiting_quota' => 'bg-yellow-100 text-yellow-800',
            'production' => 'bg-purple-100 text-purple-800',
            'qc' => 'bg-indigo-100 text-indigo-800',
            'packaging' => 'bg-pink-100 text-pink-800',
            'shipping' => 'bg-green-100 text-green-800',
            'delivered' => 'bg-emerald-100 text-emerald-800'
        ];
        
        return $colors[$this->stage] ?? 'bg-gray-100 text-gray-800';
    }
}
