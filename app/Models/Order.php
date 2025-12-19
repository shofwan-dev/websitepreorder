<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'transaction_id',
        'product_id',
        'batch_id',
        'user_id',
        'customer_name',
        'customer_city',
        'customer_phone',
        'customer_email',
        'customer_address',
        'quantity',
        'price',
        'amount',
        'total_amount',
        'payment_status',
        'status',
        'ipaymu_status',
        'is_verified',
        'is_displayed',
        'notes',
        'ipaymu_transaction_id',
        'ipaymu_payment_url',
        'ipaymu_session_id',
        'payment_expired_at',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'is_displayed' => 'boolean',
        'price' => 'decimal:2',
        'amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'verified_at' => 'datetime',
        'payment_expired_at' => 'datetime',
        'paid_at' => 'datetime',
    ];
    
    protected $appends = ['masked_name'];
    
    // Relationship dengan Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    // Relationship dengan User (jika ada)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Accessor untuk masked name
    public function getMaskedNameAttribute()
    {
        $name = $this->customer_name;
        
        if (strlen($name) <= 3) {
            return $name . '***';
        }
        
        $firstThree = substr($name, 0, 3);
        $remaining = str_repeat('*', max(0, strlen($name) - 3));
        
        return $firstThree . $remaining;
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

}