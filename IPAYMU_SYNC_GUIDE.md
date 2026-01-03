# ✅ Cara yang BENAR: Sync Payment Status dengan iPaymu

## ⚠️ PENTING: Perbedaan Mark Paid vs Sync

### ❌ **JANGAN Gunakan (Hanya untuk Testing UI)**
```
GET /test/order/17/mark-paid
```
**Masalah:**
- Update database lokal saja
- **TIDAK** cek ke iPaymu
- **TIDAK** ada verifikasi pembayaran real
- Data **TIDAK SINKRON** dengan iPaymu
- ⚠️ **HANYA untuk development/testing tampilan**

---

### ✅ **GUNAKAN INI (Production Ready)**
```
GET /ipaymu/sync-order/17
```
**Keuntungan:**
- ✅ Cek status **LANGSUNG ke iPaymu API**
- ✅ Verifikasi pembayaran **REAL**
- ✅ Data **SINKRON** dengan iPaymu
- ✅ Aman untuk **PRODUCTION**

---

## 🔄 Cara Sync Status dengan iPaymu

### **Metode 1: Sync Single Order (Recommended)**

**URL:**
```
GET http://localhost/ipaymu/sync-order/17
```

**Apa yang Terjadi:**
1. ✅ Ambil `transaction_id` atau `session_id` dari order
2. ✅ Kirim request ke **iPaymu Check Transaction API**
3. ✅ Terima status **REAL** dari iPaymu
4. ✅ Update database sesuai status dari iPaymu
5. ✅ Return detail lengkap

**Response:**
```json
{
  "success": true,
  "message": "Status berhasil disinkronkan dengan iPaymu",
  "order": {
    "id": 17,
    "old_payment_status": "pending",
    "new_payment_status": "paid",
    "paid_at": "2026-01-03 08:45:00",
    "status_changed": true
  },
  "ipaymu_data": {
    "transaction_id": "189404",
    "status": "berhasil",
    "status_code": 1,
    "amount": "150000",
    "payment_method": "va",
    "payment_channel": "bca"
  }
}
```

---

### **Metode 2: Sync All Pending Orders**

**URL:**
```
GET http://localhost/ipaymu/sync-all-pending
```

**Apa yang Terjadi:**
1. ✅ Ambil semua order dengan status `pending`
2. ✅ Cek setiap order ke iPaymu API
3. ✅ Update status yang berubah
4. ✅ Return summary hasil sync

**Response:**
```json
{
  "success": true,
  "message": "Sync completed",
  "total_orders": 3,
  "results": [
    {
      "order_id": 17,
      "old_status": "pending",
      "new_status": "paid",
      "synced": true
    },
    {
      "order_id": 16,
      "status": "pending",
      "synced": false,
      "reason": "No change needed"
    },
    {
      "order_id": 15,
      "status": "pending",
      "synced": false,
      "reason": "No change needed"
    }
  ]
}
```

---

## 📋 Status Mapping dari iPaymu

### String Status (Sandbox)
| iPaymu Status | Database Status | Keterangan |
|--------------|----------------|------------|
| "berhasil" | paid | ✅ Pembayaran berhasil |
| "pending" | pending | ⏳ Menunggu pembayaran |
| "expired" | expired | ⏰ Pembayaran kadaluarsa |
| "gagal" | failed | ❌ Pembayaran gagal |
| "refund" | refunded | 💰 Pembayaran di-refund |

### Integer Status Code (Production)
| Status Code | Database Status | Keterangan |
|------------|----------------|------------|
| 1 | paid | ✅ Pembayaran berhasil |
| 0 | pending | ⏳ Menunggu pembayaran |
| -2 | expired | ⏰ Pembayaran kadaluarsa |
| 5 | failed | ❌ Pembayaran gagal |
| 6 | refunded | 💰 Pembayaran di-refund |
| 7 | expired | ⏰ Pembayaran kadaluarsa |

---

## 🎯 Untuk Order ID 17

### **Step 1: Sync dengan iPaymu**
```
GET http://localhost/ipaymu/sync-order/17
```

### **Step 2: Cek Response**
Pastikan:
- `success` = `true`
- `status_changed` = `true`
- `new_payment_status` = `"paid"`

### **Step 3: Verifikasi di Browser**
```
http://localhost/my/orders/17
```

Status seharusnya sudah "Lunas" dengan badge hijau.

---

## 🔍 Troubleshooting

### **Error: "Order tidak memiliki transaction ID"**
**Penyebab:** Order belum pernah create payment ke iPaymu

**Solusi:**
1. Klik "Bayar Sekarang" di halaman order
2. Setelah redirect ke iPaymu, transaction_id akan tersimpan
3. Baru bisa sync

---

### **Error: "Gagal mengecek status di iPaymu"**
**Penyebab:** API iPaymu tidak merespon atau credentials salah

**Solusi:**
1. Cek credentials di `.env`:
   ```
   IPAYMU_VA=1179000899
   IPAYMU_API_KEY=your-api-key
   IPAYMU_ENVIRONMENT=sandbox
   ```
2. Test koneksi:
   ```
   GET /admin/settings/payment/test
   ```

---

### **Status di iPaymu "berhasil" tapi di database masih "pending"**
**Penyebab:** Belum sync

**Solusi:**
```
GET /ipaymu/sync-order/17
```

---

## 📊 Comparison: Manual vs Sync vs Callback

| Metode | Verifikasi iPaymu | Production Ready | Use Case |
|--------|------------------|------------------|----------|
| **Manual Mark Paid** | ❌ Tidak | ❌ Tidak | Testing UI only |
| **Sync API** | ✅ Ya | ✅ Ya | Manual check status |
| **Callback** | ✅ Ya | ✅ Ya | Auto update (best) |

---

## 💡 Best Practice

### **Development/Testing:**
1. ✅ Gunakan `/ipaymu/sync-order/{id}` untuk cek status real
2. ✅ Gunakan `/ipaymu/sync-all-pending` untuk bulk sync
3. ❌ Hindari `/test/order/{id}/mark-paid` kecuali untuk test UI

### **Production:**
1. ✅ Callback otomatis dari iPaymu (best)
2. ✅ Sync API sebagai fallback jika callback gagal
3. ✅ Cron job untuk sync pending orders secara berkala

---

## 🚀 Quick Command

### Sync Order 17 dengan iPaymu:
```bash
# Via browser
http://localhost/ipaymu/sync-order/17

# Via curl
curl http://localhost/ipaymu/sync-order/17
```

### Sync Semua Pending Orders:
```bash
# Via browser
http://localhost/ipaymu/sync-all-pending

# Via curl
curl http://localhost/ipaymu/sync-all-pending
```

---

**Created:** 2026-01-03  
**Purpose:** Sync payment status dengan iPaymu (CARA YANG BENAR)  
**Status:** Production Ready ✅
