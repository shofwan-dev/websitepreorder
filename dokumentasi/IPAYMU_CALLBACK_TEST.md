# Test iPaymu Callback

## Cara Test Callback Notify Simulation

### 1. Via Browser/Postman
Gunakan URL berikut untuk simulasi callback berhasil:

```
POST http://localhost/ipaymu/callback
Content-Type: application/json

{
  "trx_id": "TRANSACTION_ID_DARI_ORDER",
  "status": "1",
  "reference_id": "ORDER-{ORDER_ID}"
}
```

### 2. Cek Status Order
Setelah callback, cek di database:
```sql
SELECT id, payment_status, paid_at, ipaymu_transaction_id 
FROM orders 
WHERE id = {ORDER_ID};
```

### 3. Cek Log
```bash
tail -f storage/logs/laravel.log
```

## Troubleshooting

### Callback tidak diterima?
1. Pastikan URL accessible: `http://localhost/ipaymu/callback`
2. Cek CSRF exception di `app/Http/Middleware/VerifyCsrfToken.php`
3. Pastikan method POST

### Status tidak berubah?
1. Cek log untuk error
2. Pastikan `trx_id` atau `reference_id` ada di request
3. Pastikan order ditemukan di database
4. Cek format status (harus "1" atau 1 untuk berhasil)

## Format Status yang Didukung

### String Format
- "berhasil" → paid
- "pending" → pending  
- "expired" → expired
- "gagal" → failed
- "refund" → refunded

### Integer Format
- 1 → paid
- 0 → pending
- -2 → expired
- 5 → failed
- 6 → refunded
- 7 → expired

## Contoh Request Lengkap

```json
{
  "trx_id": "189404",
  "sid": "20c1f44e-d245-4271-abfb-45f0a93805fe",
  "status": "1",
  "reference_id": "ORDER-1",
  "payment_method": "va",
  "payment_channel": "bca"
}
```

## Expected Response

```json
{
  "status": "success",
  "message": "Callback processed"
}
```
