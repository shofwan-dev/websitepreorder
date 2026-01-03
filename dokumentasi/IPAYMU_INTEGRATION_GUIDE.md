# Panduan Integrasi iPaymu Payment Gateway

## Deskripsi
Aplikasi ini telah terintegrasi dengan iPaymu Payment Gateway untuk memproses pembayaran online. User dapat melakukan pembayaran untuk pesanan mereka melalui berbagai metode pembayaran yang disediakan oleh iPaymu.

## Fitur yang Tersedia

### 1. Payment Processing
- **Create Payment**: Membuat link pembayaran untuk order
- **Check Transaction**: Mengecek status transaksi pembayaran
- **Check Balance**: Mengecek saldo iPaymu merchant
- **Transaction History**: Melihat riwayat transaksi

### 2. COD (Cash on Delivery) Features
- **Shipping Calculate**: Menghitung biaya pengiriman COD
- **Package Tracking**: Melacak paket COD

## Alur Pembayaran

### User Flow:
1. User membuat order di halaman `/my/orders/create`
2. Setelah order dibuat, user diarahkan ke halaman detail order `/my/orders/{id}`
3. Di halaman detail order, user akan melihat tombol **"Bayar Sekarang"**
4. Saat tombol diklik:
   - System akan memanggil iPaymu API untuk membuat payment link
   - User akan diredirect ke halaman pembayaran iPaymu
   - User memilih metode pembayaran (VA, E-wallet, Retail, dll)
   - User menyelesaikan pembayaran
5. Setelah pembayaran:
   - **Berhasil**: User diredirect ke halaman return dan order status diupdate menjadi 'paid'
   - **Gagal/Cancel**: User diredirect ke halaman cancel

### Callback Flow:
1. iPaymu mengirim notifikasi callback ke `/ipaymu/callback`
2. System verifikasi dan update status pembayaran order
3. Order status diupdate berdasarkan status dari iPaymu:
   - Status 1: Berhasil → `payment_status = 'paid'`
   - Status 6: Refund → `payment_status = 'refunded'`
   - Status 7: Expired → `payment_status = 'expired'`

## Endpoints API yang Digunakan

### 1. Create Payment
```
POST https://sandbox.ipaymu.com/api/v2/payment
```
**Headers:**
- `Content-Type: application/json`
- `va: {YOUR_VA}`
- `signature: {GENERATED_SIGNATURE}`
- `timestamp: {TIMESTAMP}`

**Body:**
```json
{
  "product": ["Product Name"],
  "qty": [1],
  "price": [100000],
  "returnUrl": "http://your-domain.com/ipaymu/return",
  "cancelUrl": "http://your-domain.com/ipaymu/cancel",
  "notifyUrl": "http://your-domain.com/ipaymu/callback",
  "referenceId": "ORDER-123",
  "buyerName": "Customer Name",
  "buyerEmail": "customer@email.com",
  "buyerPhone": "081234567890"
}
```

### 2. Check Transaction
```
POST https://sandbox.ipaymu.com/api/v2/transaction
```
**Body:**
```json
{
  "transactionId": "4719",
  "account": "1179000899"
}
```

### 3. Check Balance
```
POST https://sandbox.ipaymu.com/api/v2/balance
```
**Body:**
```json
{
  "account": "1179000899"
}
```

### 4. Transaction History
```
POST https://sandbox.ipaymu.com/api/v2/history
```
**Body:**
```json
{
  "account": "1179000899",
  "startdate": "2023-01-01",
  "enddate": "2023-12-31",
  "page": 1,
  "limit": 20
}
```

### 5. Shipping Calculate (COD)
```
POST https://sandbox.ipaymu.com/api/v2/cod/shipping-calculate
```
**Body (Form-Data):**
- `destination_area_id`: 17473
- `pickup_area_id`: 17473
- `weight`: 1 (in kilogram)
- `amount`: 100000

### 6. Package Tracking (COD)
```
POST https://sandbox.ipaymu.com/api/v2/cod/tracking
```
**Body (Form-Data):**
- `awb`: KOMSHIP00137699862
- `transaction_id`: 14919771

## File-file yang Terlibat

### Controllers
1. **UserOrderController** (`app/Http/Controllers/User/OrderController.php`)
   - Method `processPayment()`: Memproses pembayaran order
   - Method `show()`: Menampilkan detail order dan button pembayaran

2. **IPaymuCallbackController** (`app/Http/Controllers/IPaymuCallbackController.php`)
   - Method `callback()`: Handle callback/notification dari iPaymu
   - Method `return()`: Handle return URL setelah pembayaran
   - Method `cancel()`: Handle cancel URL jika user cancel pembayaran

### Services
**IPaymuService** (`app/Services/IPaymuService.php`)
- `createPayment()`: Membuat payment request
- `checkTransaction()`: Cek status transaksi
- `checkBalance()`: Cek saldo merchant
- `getHistoryTransaction()`: Get transaction history
- `calculateShipping()`: Hitung biaya pengiriman COD
- `trackPackage()`: Tracking paket COD
- `generateSignature()`: Generate signature untuk authenticasi API

### Views
**Order Show Page** (`resources/views/user/orders/show.blade.php`)
- Menampilkan detail order
- Tombol "Bayar Sekarang" untuk membuat payment
- Link "Lanjutkan Pembayaran" jika payment link sudah ada
- Status pembayaran dan expired date

### Routes
**web.php** (`routes/web.php`)
```php
// User order payment
Route::post('/my/orders/{order}/pay', [UserOrderController::class, 'processPayment'])
    ->name('user.orders.pay');

// iPaymu callbacks (public)
Route::post('/ipaymu/callback', [IPaymuCallbackController::class, 'callback'])
    ->name('ipaymu.callback');
Route::get('/ipaymu/return', [IPaymuCallbackController::class, 'return'])
    ->name('ipaymu.return');
Route::get('/ipaymu/cancel', [IPaymuCallbackController::class, 'cancel'])
    ->name('ipaymu.cancel');
```

### Database
**Orders Table** memiliki kolom untuk iPaymu:
- `ipaymu_transaction_id`: ID transaksi dari iPaymu
- `ipaymu_payment_url`: URL pembayaran dari iPaymu
- `ipaymu_session_id`: Session ID dari iPaymu
- `payment_expired_at`: Waktu expired pembayaran
- `payment_status`: Status pembayaran (pending/paid/refunded/expired)
- `paid_at`: Waktu pembayaran berhasil

## Konfigurasi

### Environment Variables
Di file `.env`, pastikan sudah ada:
```
IPAYMU_VA=1179000899
IPAYMU_API_KEY=your-api-key-here
IPAYMU_ENVIRONMENT=sandbox
IPAYMU_SANDBOX_URL=https://sandbox.ipaymu.com/api/v2
IPAYMU_PRODUCTION_URL=https://my.ipaymu.com/api/v2
```

### Config File
File `config/services.php`:
```php
'ipaymu' => [
    'va' => env('IPAYMU_VA'),
    'api_key' => env('IPAYMU_API_KEY'),
    'environment' => env('IPAYMU_ENVIRONMENT', 'sandbox'),
    'sandbox_url' => env('IPAYMU_SANDBOX_URL', 'https://sandbox.ipaymu.com/api/v2'),
    'production_url' => env('IPAYMU_PRODUCTION_URL', 'https://my.ipaymu.com/api/v2'),
],
```

## Testing

### 1. Test Connection
Gunakan fitur test payment di admin panel:
```
/admin/settings/payment/test
```

### 2. Test Create Payment
1. Login sebagai user
2. Buat order baru
3. Klik tombol "Bayar Sekarang"
4. Cek log di `storage/logs/laravel.log` untuk melihat request/response

### 3. Test Callback
Gunakan Postman atau cURL untuk simulate callback:
```bash
curl --location 'http://your-domain.com/ipaymu/callback' \
--header 'Content-Type: application/json' \
--data '{
    "trx_id": "4719",
    "status": "1"
}'
```

## Status Codes iPaymu

- **-2**: Expired
- **0**: Pending
- **1**: Berhasil (Paid)
- **2**: Batal
- **3**: Refund
- **4**: Error
- **5**: Gagal
- **6**: Berhasil - Unsettled
- **7**: Escrow

## Production Checklist

Sebelum go live ke production:

- [ ] Ganti environment ke `production` di `.env`
- [ ] Update VA dan API Key dengan credentials production
- [ ] Test pembayaran dengan amount kecil
- [ ] Setup webhook/callback URL di iPaymu dashboard
- [ ] Monitor log untuk error/issue
- [ ] Pastikan SSL/HTTPS aktif (required untuk callback)

## Troubleshooting

### Payment Link tidak dibuat
- Cek API credentials (VA dan API Key)
- Cek signature generation
- Lihat log error di `storage/logs/laravel.log`

### Callback tidak diterima
- Pastikan URL callback accessible dari internet
- Cek firewall/security settings
- Pastikan endpoint `/ipaymu/callback` tidak protected oleh CSRF middleware

### Payment status tidak update
- Cek log callback di `storage/logs/laravel.log`
- Pastikan transaction ID tersimpan di database
- Verifikasi status code dari iPaymu

## Support & Documentation

- **iPaymu Official Docs**: https://ipaymu.com/docs
- **Signature Documentation**: https://storage.googleapis.com/ipaymu-docs/ipaymu-api/iPaymu-signature-documentation-v2.pdf
- **Support Email**: support@ipaymu.com
