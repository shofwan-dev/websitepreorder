<!-- resources/views/livewire/po-progress.blade.php -->
<div class="bg-white rounded-xl shadow-lg p-6">
    <h3 class="text-2xl font-serif-islamic text-warm-gold-700 mb-4">
        PO Batch #{{ $batchNumber }}
    </h3>
    
    @if($product)
        <!-- Progress Bar -->
        <div class="mb-4">
            <div class="flex justify-between text-sm mb-2">
                <span class="font-semibold">Progress Kuota</span>
                <span class="text-warm-gold-600">{{ $currentOrders }} / {{ $product->min_quota }}</span>
            </div>
            <div class="h-4 bg-gray-200 rounded-full overflow-hidden">
                <div 
                    class="h-full bg-gradient-to-r from-warm-gold-400 to-warm-gold-600 transition-all duration-500"
                    style="width: {{ $progress }}%"
                ></div>
            </div>
        </div>
        
        <!-- Counter & Urgency Message -->
        <div class="text-center p-4 bg-warm-gold-50 rounded-lg">
            @if($remainingSlots > 0)
                <p class="text-lg font-semibold text-warm-gold-800">
                    ⏳ {{ $remainingSlots }} slot lagi agar produksi dimulai!
                </p>
                <p class="text-sm text-gray-600 mt-2">
                    Segera bergabung sebelum kuota terpenuhi
                </p>
            @else
                <p class="text-lg font-semibold text-green-600">
                    ✅ Kuota terpenuhi! Produksi akan segera dimulai.
                </p>
            @endif
        </div>
    @else
        <!-- Fallback jika product tidak ditemukan -->
        <div class="text-center p-6">
            <p class="text-lg text-red-600 mb-4">
                ⚠️ Produk tidak ditemukan
            </p>
            <div class="mb-4">
                <div class="flex justify-between text-sm mb-2">
                    <span class="font-semibold">Progress Kuota</span>
                    <span class="text-warm-gold-600">0 / 10</span>
                </div>
                <div class="h-4 bg-gray-200 rounded-full overflow-hidden">
                    <div 
                        class="h-full bg-gradient-to-r from-warm-gold-400 to-warm-gold-600 transition-all duration-500"
                        style="width: 0%"
                    ></div>
                </div>
            </div>
            <p class="text-lg font-semibold text-warm-gold-800">
                ⏳ 10 slot lagi agar produksi dimulai!
            </p>
        </div>
    @endif
</div>