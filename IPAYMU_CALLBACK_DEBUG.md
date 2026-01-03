# iPaymu Callback Debugging Guide

## 🐛 Problem: Status tidak berubah setelah callback simulation

### Langkah-langkah Debugging:

## 1️⃣ Cek Order yang Ada

Buka browser dan akses:
```
http://localhost/ipaymu/debug-orders
```

Ini akan menampilkan 10 order terakhir dengan informasi:
- Order ID
- Payment Status
- iPaymu Transaction ID
- iPaymu Session ID
- Payment URL status

**Catat:**
- Order ID yang ingin di-test
- Session ID (ipaymu_session_id)
- Transaction ID (ipaymu_transaction_id) jika ada

---

## 2️⃣ Test Callback dengan Debug Endpoint

### Menggunakan Postman/Thunder Client:

**URL:**
```
POST http://localhost/ipaymu/debug-callback
```

**Headers:**
```
Content-Type: application/json
```

**Body (pilih salah satu format):**

#### Format 1: Menggunakan Transaction ID
```json
{
  "trx_id": "PASTE_TRANSACTION_ID_DISINI",
  "status": "1"
}
```

#### Format 2: Menggunakan Session ID
```json
{
  "sid": "PASTE_SESSION_ID_DISINI",
  "status": "1"
}
```

#### Format 3: Menggunakan Reference ID
```json
{
  "reference_id": "ORDER-1",
  "status": "1"
}
```

**Response akan menunjukkan:**
```json
{
  "debug": {
    "callback_received": true,
    "timestamp": "2026-01-03 08:30:00",
    "extracted_data": {
      "transaction_id": "...",
      "reference_id": "...",
      "status": "1",
      "status_type": "string"
    },
    "order_search": {
      "found": true,
      "search_method": "ipaymu_session_id",
      "order_id": 1,
      "current_payment_status": "pending",
      "ipaymu_transaction_id": "...",
      "ipaymu_session_id": "..."
    },
    "status_interpretation": {
      "original_value": "1",
      "type": "string",
      "as_string_lower": "1",
      "as_integer": 1,
      "will_be_marked_as": "PAID"
    }
  }
}
```

---

## 3️⃣ Analisa Response Debug

### ✅ Jika Order Found = TRUE:
Order ditemukan! Lanjut ke langkah 4.

### ❌ Jika Order Found = FALSE:
**Problem:** Order tidak ditemukan

**Solusi:**
1. Cek apakah order ID benar
2. Pastikan session_id atau transaction_id sesuai dengan yang ada di database
3. Gunakan format reference_id: `ORDER-{id}` (contoh: ORDER-1)

---

## 4️⃣ Test dengan Callback Asli

Setelah yakin data sudah benar, test dengan endpoint asli:

**URL:**
```
POST http://localhost/ipaymu/callback
```

**Body (gunakan data yang sama dengan debug):**
```json
{
  "trx_id": "PASTE_TRANSACTION_ID_DISINI",
  "status": "1"
}
```

**Expected Response:**
```json
{
  "status": "success",
  "message": "Callback processed"
}
```

---

## 5️⃣ Verifikasi Status Berubah

### Cara 1: Cek via Debug Orders
```
GET http://localhost/ipaymu/debug-orders
```

Lihat apakah `payment_status` sudah berubah menjadi `paid`

### Cara 2: Cek di Browser
Buka halaman order detail:
```
http://localhost/my/orders/{ORDER_ID}
```

Status pembayaran seharusnya sudah "Lunas"

### Cara 3: Cek Database
```sql
SELECT id, payment_status, paid_at 
FROM orders 
WHERE id = {ORDER_ID};
```

---

## 📋 Format Status yang Didukung

### String Format (Sandbox)
| Status | Hasil |
|--------|-------|
| "berhasil" | PAID |
| "1" | PAID |
| "pending" | PENDING |
| "0" | PENDING |
| "expired" | EXPIRED |
| "gagal" | FAILED |

### Integer Format (Production)
| Status | Hasil |
|--------|-------|
| 1 | PAID |
| 0 | PENDING |
| -2 | EXPIRED |
| 5 | FAILED |
| 6 | REFUNDED |
| 7 | EXPIRED |

---

## 🔍 Troubleshooting Checklist

### ✅ Callback diterima tapi status tidak berubah?

**Check 1: Order ditemukan?**
- [ ] Cek response debug: `order_search.found` = true
- [ ] Jika false, cek transaction_id/session_id/reference_id

**Check 2: Status format benar?**
- [ ] Cek response debug: `status_interpretation.will_be_marked_as`
- [ ] Harus "PAID" untuk status berhasil
- [ ] Jika "UNKNOWN", cek format status yang dikirim

**Check 3: Callback masuk ke server?**
- [ ] Cek log: `tail -f storage/logs/laravel.log`
- [ ] Harus ada log "iPaymu Callback Received"

**Check 4: CSRF Token?**
- [ ] Pastikan `/ipaymu/callback` ada di CSRF exception
- [ ] File: `app/Http/Middleware/VerifyCsrfToken.php`

---

## 📝 Contoh Lengkap Test Flow

### Step 1: Lihat order yang ada
```bash
curl http://localhost/ipaymu/debug-orders
```

### Step 2: Copy session_id dari response
```json
{
  "orders": [
    {
      "id": 1,
      "ipaymu_session_id": "abc-123-def-456",
      "payment_status": "pending"
    }
  ]
}
```

### Step 3: Test dengan debug callback
```bash
curl -X POST http://localhost/ipaymu/debug-callback \
  -H "Content-Type: application/json" \
  -d '{
    "sid": "abc-123-def-456",
    "status": "1"
  }'
```

### Step 4: Cek response - pastikan found=true
```json
{
  "debug": {
    "order_search": {
      "found": true,
      "order_id": 1
    },
    "status_interpretation": {
      "will_be_marked_as": "PAID"
    }
  }
}
```

### Step 5: Kirim ke callback asli
```bash
curl -X POST http://localhost/ipaymu/callback \
  -H "Content-Type: application/json" \
  -d '{
    "sid": "abc-123-def-456",
    "status": "1"
  }'
```

### Step 6: Verifikasi
```bash
curl http://localhost/ipaymu/debug-orders
```

Seharusnya `payment_status` sudah `paid`!

---

## 🎯 Quick Fix

Jika masih tidak berubah setelah semua langkah:

### 1. Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### 2. Restart Server
Jika menggunakan `php artisan serve`, restart server.

### 3. Cek Log Error
```bash
tail -100 storage/logs/laravel.log | grep -i error
```

---

## 📞 Jika Masih Bermasalah

Kirim informasi berikut:

1. **Response dari debug-callback:**
   ```
   POST /ipaymu/debug-callback
   ```

2. **Response dari debug-orders:**
   ```
   GET /ipaymu/debug-orders
   ```

3. **Log terakhir:**
   ```bash
   tail -50 storage/logs/laravel.log
   ```

---

**Created:** 2026-01-03  
**Purpose:** Debugging iPaymu callback issues  
**Status:** Ready to use 🚀
