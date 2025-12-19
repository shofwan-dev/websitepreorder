@extends('layouts.admin')

@section('title', 'Pesanan Pending')
@section('page-title', 'Pesanan Pending')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1 class="page-title">Pesanan Pending</h1>
        <p class="text-muted">Pesanan yang membutuhkan konfirmasi</p>
    </div>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary">
        <i class="fas fa-list me-2"></i> Semua Pesanan
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Produk</th>
                        <th>Total</th>
                        <th>Pembayaran</th>
                        <th>Waktu</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td><strong>#{{ $order->id }}</strong></td>
                        <td>
                            <div>{{ $order->customer_name ?? $order->user->name ?? '-' }}</div>
                            <small class="text-muted">{{ $order->customer_phone ?? '-' }}</small>
                        </td>
                        <td>{{ $order->product->name ?? '-' }}</td>
                        <td>Rp {{ number_format($order->total_amount ?? 0, 0, ',', '.') }}</td>
                        <td>
                            @if($order->payment_status == 'paid')
                                <span class="badge bg-success">Lunas</span>
                            @else
                                <span class="badge bg-danger">Belum</span>
                            @endif
                        </td>
                        <td>
                            {{ $order->created_at->diffForHumans() }}
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-check me-1"></i> Proses
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="fas fa-check-circle fa-3x text-success mb-3 d-block"></i>
                            <h5>Tidak Ada Pesanan Pending</h5>
                            <p class="text-muted mb-0">Semua pesanan sudah diproses</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($orders->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $orders->links() }}
</div>
@endif
@endsection
