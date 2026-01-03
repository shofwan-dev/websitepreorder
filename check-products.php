<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CHECKING PRODUCTS ===\n\n";

$products = \App\Models\Product::select('id', 'name', 'images')->limit(5)->get();

if ($products->count() > 0) {
    foreach ($products as $product) {
        echo "ID: {$product->id}\n";
        echo "Name: {$product->name}\n";
        echo "Images: " . json_encode($product->images) . "\n";
        echo "---\n";
    }
} else {
    echo "No products found in database.\n";
}

echo "\n=== CHECKING ORDERS ===\n\n";

$orders = \App\Models\Order::with('product')
    ->where('payment_status', 'paid')
    ->limit(3)
    ->get();

if ($orders->count() > 0) {
    foreach ($orders as $order) {
        echo "Order ID: {$order->id}\n";
        echo "Product: " . ($order->product->name ?? 'N/A') . "\n";
        echo "Customer: {$order->customer_name}\n";
        echo "Product Images: " . json_encode($order->product->images ?? null) . "\n";
        echo "---\n";
    }
} else {
    echo "No paid orders found.\n";
}
