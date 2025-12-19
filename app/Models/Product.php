<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'price', 'min_quota', 
        'current_batch', 'images', 'video_url', 'specifications', 'is_active'
    ];
    
    protected $casts = [
        'images' => 'array',
        'specifications' => 'array',
        'is_active' => 'boolean'
    ];
    
    // Relationship dengan Order
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
    
    // Relationship dengan ProductionTimeline
    public function productionTimelines(): HasMany
    {
        return $this->hasMany(ProductionTimeline::class);
    }
    
    // Accessor untuk jumlah order yang sudah bayar
    public function getPaidOrdersCountAttribute(): int
    {
        return $this->orders()
            ->where('payment_status', 'paid')
            ->where('is_verified', true)
            ->count();
    }
    
    // Accessor untuk persentase progress
    public function getProgressPercentageAttribute(): float
    {
        if ($this->min_quota == 0) {
            return 0;
        }
        
        $percentage = ($this->paid_orders_count / $this->min_quota) * 100;
        return min(100, $percentage);
    }
    
    // Get current production timeline
    public function getCurrentTimelineAttribute()
    {
        return $this->productionTimelines()
            ->where('batch_number', $this->current_batch)
            ->first();
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

}