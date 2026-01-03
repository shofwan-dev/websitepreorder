# Quick Fix: Update Order Payment Status

## 🚀 Cara Tercepat - Via Browser

Untuk langsung menandai order sebagai LUNAS, akses URL berikut di browser:

```
http://localhost/test/order/17/mark-paid
```

Ganti `17` dengan Order ID yang ingin diupdate.

**Hasil:**
- ✅ Payment status berubah menjadi `paid`
- ✅ Paid at terisi dengan timestamp sekarang
- ✅ Redirect ke halaman order detail
- ✅ Status di halaman berubah menjadi "Lunas"

---

## 🔧 Cara Alternatif - Via Artisan Command

Jika prefer menggunakan command line:

```bash
php artisan order:update-payment 17 paid
```

**Parameters:**
- `17` = Order ID
- `paid` = Status (paid/pending/partial/refunded/expired/failed)

**Contoh:**
```bash
# Mark order 17 as paid
php artisan order:update-payment 17 paid

# Mark order 17 as pending
php artisan order:update-payment 17 pending

# Mark order 17 as refunded
php artisan order:update-payment 17 refunded
```

---

## 📋 Verifikasi

Setelah update, cek di:

### 1. Browser
```
http://localhost/my/orders/17
```
Status seharusnya "Lunas" dengan badge hijau.

### 2. Debug Orders
```
http://localhost/ipaymu/debug-orders
```
Cek `payment_status` dan `paid_at` untuk order 17.

### 3. Database (Optional)
```sql
SELECT id, customer_name, payment_status, paid_at 
FROM orders 
WHERE id = 17;
```

---

## 🎯 Untuk Semua Order Pending

Jika ingin update semua order pending sekaligus:

```bash
# Order 17
php artisan order:update-payment 17 paid

# Order 16
php artisan order:update-payment 16 paid

# Order 15
php artisan order:update-payment 15 paid
```

Atau via browser:
```
http://localhost/test/order/17/mark-paid
http://localhost/test/order/16/mark-paid
http://localhost/test/order/15/mark-paid
```

---

## 🔍 Troubleshooting iPaymu Callback

Jika callback dari iPaymu masih tidak bekerja:

### 1. Cek Callback Handler
Pastikan callback controller bisa menemukan order:

```
POST http://localhost/ipaymu/debug-callback
Content-Type: application/json

{
  "sid": "ec2d85d1-e978-4634-bbc5-a7bb82878485",
  "status": "1"
}
```

### 2. Cek Response
Response harus menunjukkan:
- `order_search.found` = `true`
- `status_interpretation.will_be_marked_as` = `"PAID"`

### 3. Jika Found = True tapi Status Tidak Berubah
Kemungkinan ada error saat save. Cek dengan:

```bash
# Clear cache
php artisan cache:clear
php artisan config:clear

# Test lagi
POST http://localhost/ipaymu/callback
{
  "sid": "ec2d85d1-e978-4634-bbc5-a7bb82878485",
  "status": "1"
}
```

---

## 💡 Tips

### Untuk Testing Cepat
Gunakan route `/test/order/{id}/mark-paid` - paling cepat!

### Untuk Production
Pastikan callback iPaymu berfungsi dengan baik. Route test hanya untuk development.

### Untuk Debugging
Gunakan `/ipaymu/debug-callback` untuk melihat detail proses callback.

---

**Created:** 2026-01-03  
**Purpose:** Quick fix untuk update payment status  
**Status:** Ready to use! 🎉
