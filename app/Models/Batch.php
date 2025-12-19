<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Batch extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_number',
        'product_id',
        'target_quantity',
        'current_quantity',
        'status',
        'production_start_date',
        'estimated_completion_date',
        'actual_completion_date',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'production_start_date' => 'date',
        'estimated_completion_date' => 'date',
        'actual_completion_date' => 'date',
    ];

    /**
     * Get the product that owns the batch.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who created the batch.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the orders for the batch.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Calculate progress percentage.
     */
    public function getProgressPercentageAttribute(): float
    {
        if ($this->target_quantity == 0) {
            return 0;
        }
        return min(100, ($this->current_quantity / $this->target_quantity) * 100);
    }

    /**
     * Check if batch is completed.
     */
    public function getIsCompletedAttribute(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if batch is active.
     */
    public function getIsActiveAttribute(): bool
    {
        return !in_array($this->status, ['completed', 'cancelled']);
    }

    /**
     * Get status color for display.
     */
    public function getStatusColorAttribute(): string
    {
        $colors = [
            'planning' => 'secondary',
            'collecting' => 'info',
            'production' => 'primary',
            'qc' => 'warning',
            'packaging' => 'info',
            'shipping' => 'success',
            'completed' => 'success',
            'cancelled' => 'danger'
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Get status label for display.
     */
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'planning' => 'Perencanaan',
            'collecting' => 'Pengumpulan Pesanan',
            'production' => 'Produksi',
            'qc' => 'Quality Control',
            'packaging' => 'Pengemasan',
            'shipping' => 'Pengiriman',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan'
        ];

        return $labels[$this->status] ?? $this->status;
    }
}