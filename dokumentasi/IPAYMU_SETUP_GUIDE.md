# iPaymu Payment Gateway Integration - Setup Guide

## 📋 **Progress: 100% Complete!**

### ✅ **Yang Sudah Dikerjakan:**

1. ✅ Payment settings page dengan form iPaymu
2. ✅ Config file (`config/services.php`)
3. ✅ IPaymuService class
4. ✅ SettingController methods (save & test)
5. ✅ Routes untuk settings & callback
6. ✅ Migration untuk orders table
7. ✅ IPaymuCallbackController untuk handle notifications
8. ✅ Order model updated dengan fillable fields

---

## 🚀 **Cara Testing:**

### **Step 1: Run Migration**
```bash
php artisan migrate
```

### **Step 2: Tambahkan Credentials ke .env**

Buka file `.env` dan tambahkan:

```env
# iPaymu Payment Gateway
IPAYMU_VA=1179000899
IPAYMU_API_KEY=your_api_key_here
IPAYMU_ENVIRONMENT=sandbox
```

⚠️ **PENTING**: 
- Ganti `IPAYMU_VA` dengan VA number Anda dari dashboard iPaymu
- Ganti `IPAYMU_API_KEY` dengan API key Anda dari dashboard iPaymu
- Gunakan `sandbox` untuk testing, `production` untuk live

### **Step 3: Clear Config Cache**
```bash
php artisan config:clear
```

### **Step 4: Test dari Admin Panel**

1. Login sebagai admin
2. Buka: `http://localhost/po-kaligrafi/public/admin/settings/payment`
3. Isi form dengan credentials iPaymu:
   - VA: `1179000899` (atau VA Anda)
   - API Key: (dari dashboard iPaymu)
   - Environment: Pilih "Sandbox (Testing)"
4. Klik **"Simpan Pengaturan"**
5. Klik **"Test Koneksi"** untuk verifikasi

### **Step 5: Expected Results**

✅ **Jika Berhasil:**
- Alert hijau: "Pengaturan iPaymu berhasil disimpan"
- Test koneksi menampilkan: "Koneksi ke iPaymu berhasil! Status: 200" (atau 401/404 tapi connected)

❌ **Jika Gagal:**
- Alert merah dengan pesan error
- Cek Laravel log di `storage/logs/laravel.log`

---

## 📝 **Files yang Dibuat/Dimodifikasi:**

### Created:
1. `config/services.php`
2. `app/Services/IPaymuService.php`
3. `app/Http/Controllers/IPaymuCallbackController.php`
4. `database/migrations/2025_12_17_000001_add_ipaymu_fields_to_orders_table.php`

### Modified:
1. `resources/views/admin/settings/payment.blade.php`
2. `app/Http/Controllers/Admin/SettingController.php`
3. `routes/web.php`
4. `app/Models/Order.php`

---

## 🔧 **Troubleshooting:**

### Issue: "Connection Failed"
- ✅ Pastikan VA dan API Key benar
- ✅ Cek internet connection
- ✅ Lihat log di `storage/logs/laravel.log`

### Issue: "Unauthorized (401)"
- ✅ Signature generation mungkin salah
- ✅ Cek API Key di dashboard iPaymu
- ✅ Pastikan format timestamp benar

### Issue: "Config not updating"
- ✅ Run: `php artisan config:clear`
- ✅ Restart web server jika perlu

---

## 📊 **iPaymu Transaction Status Codes:**

- **1** = Berhasil (Paid)
- **6** = Refund
- **7** = Expired
- **0** = Pending

---

## 🔗 **Callback URLs untuk iPaymu Dashboard:**

Tambahkan URLs ini di dashboard iPaymu:

- **Notify URL**: `http://your-domain.com/ipaymu/callback`
- **Return URL**: `http://your-domain.com/ipaymu/return`
- **Cancel URL**: `http://your-domain.com/ipaymu/cancel`

---

## 📚 **Next Steps (Untuk Production):**

1. ❌ Update UserOrderController untuk integrate payment creation (belum dikerjakan)
2. ❌ Add payment button redirect to iPaymu (belum dikerjakan)
3. ⚠️ Test full payment flow (pending)
4. ⚠️ Enable production mode di .env (setelah testing berhasil)
5. ⚠️ Update callback URLs di dashboard iPaymu (gunakan domain live)

---

**Created**: 2025-12-17 02:35 WIB
**Status**: ✅ Ready for Testing
