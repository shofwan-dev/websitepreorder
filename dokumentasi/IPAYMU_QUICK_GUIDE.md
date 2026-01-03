# Quick Guide: iPaymu Payment Integration

## ⚡ Quick Start

### 1. Untuk User (Customer)
1. Buat order di halaman **Create Order**
2. Setelah order dibuat, klik tombol **"Bayar Sekarang"** di halaman detail order
3. Anda akan diarahkan ke halaman pembayaran iPaymu
4. Pilih metode pembayaran dan selesaikan pembayaran
5. Status pembayaran akan otomatis terupdate

### 2. Untuk Admin

#### Setup Environment (.env)
```bash
IPAYMU_VA=1179000899
IPAYMU_API_KEY=your-api-key-here
IPAYMU_ENVIRONMENT=sandbox
```

#### Test Payment Gateway
1. Login sebagai admin
2. Buka `/admin/settings/payment/test`
3. Test koneksi ke iPaymu

## 🔗 Routes yang Tersedia

### User Routes
- `POST /my/orders/{order}/pay` - Proses pembayaran

### Public Routes (Callback)
- `POST /ipaymu/callback` - Webhook dari iPaymu
- `GET /ipaymu/return` - Return URL setelah pembayaran
- `GET /ipaymu/cancel` - Cancel URL jika batal bayar

## 📝 Available Methods (IPaymuService)

```php
// Create payment
$ipaymu->createPayment($orderData);

// Check transaction status
$ipaymu->checkTransaction($transactionId);

// Check merchant balance
$ipaymu->checkBalance();

// Get transaction history
$ipaymu->getHistoryTransaction(['startdate' => '2024-01-01']);

// Calculate COD shipping
$ipaymu->calculateShipping([
    'destination_area_id' => '17473',
    'pickup_area_id' => '17473',
    'weight' => 1,
    'amount' => 100000
]);

// Track COD package
$ipaymu->trackPackage('AWB123', 'TRX123');
```

## 🔍 Testing

### Manual Test Order Payment
1. Login sebagai user biasa (bukan admin)
2. Buat order baru: `/my/orders/create`
3. Pilih produk, isi form, submit
4. Di halaman detail order, klik "Bayar Sekarang"
5. Cek log: `storage/logs/laravel.log`

### Test Callback (via Postman)
```bash
POST http://localhost/ipaymu/callback
Content-Type: application/json

{
  "trx_id": "4719",
  "status": "1"
}
```

## 🐛 Troubleshooting

### Payment button tidak muncul?
- Pastikan `payment_status !== 'paid'`
- Pastikan `status !== 'cancelled'`

### Error saat klik "Bayar Sekarang"?
1. Cek log: `tail -f storage/logs/laravel.log`
2. Verifikasi credentials di `.env`
3. Test koneksi: `/admin/settings/payment/test`

### Callback tidak masuk?
1. Pastikan URL callback accessible dari internet
2. Untuk local testing, gunakan ngrok atau similar
3. Cek CSRF exception di `app/Http/Middleware/VerifyCsrfToken.php`

## 📚 Full Documentation
Lihat **IPAYMU_INTEGRATION_GUIDE.md** untuk dokumentasi lengkap.

## 💡 Notes

- **Sandbox mode**: Default environment adalah sandbox untuk testing
- **Production**: Ganti `IPAYMU_ENVIRONMENT=production` dan update credentials
- **Signature**: Signature otomatis di-generate oleh IPaymuService
- **Security**: Callback URL harus HTTPS di production
