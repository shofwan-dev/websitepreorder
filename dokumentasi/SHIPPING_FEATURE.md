# Shipping Cost & Free Shipping Code Feature

## 📦 Fitur Hitung Ongkir & Kode Gratis Ongkir

Implementasi lengkap untuk:
1. **Hitung ongkir otomatis** menggunakan BinderByte API
2. **Kode gratis ongkir** dengan psikologi marketing
3. **Notifikasi marketing** yang meningkatkan konversi

---

## 🎯 Fitur yang Telah Diimplementasikan

### ✅ **1. BinderByte Service**
📁 `app/Services/BinderByteService.php`

**Methods:**
- `getProvinces()` - Ambil daftar provinsi
- `getCities($provinceId)` - Ambil daftar kota
- `getDistricts($cityId)` - Ambil daftar kecamatan
- `trackPackage($courier, $awb)` - Tracking paket
- `getCouriers()` - Daftar kurir
- `checkQuota()` - Cek quota API

### ✅ **2. Database Migration**
📁 `database/migrations/2026_01_03_043214_add_shipping_fields_to_orders_table.php`

**Kolom Baru:**
- `province_id`, `province_name`
- `city_id`, `city_name`
- `district_id`, `district_name`
- `shipping_cost` - Biaya ongkir
- `courier` - Nama kurir (JNE, SiCepat, dll)
- `courier_service` - Layanan (REG, YES, dll)
- `tracking_number` - Nomor resi
- `free_shipping_code` - Kode yang digunakan

### ✅ **3. Order Model Update**
📁 `app/Models/Order.php`

**Fillable & Casts:**
- Semua field shipping ditambahkan
- `shipping_cost` di-cast sebagai decimal

### ✅ **4. Admin Settings - Kode Gratis Ongkir**
📁 `resources/views/admin/settings/website.blade.php`

**Fitur:**
- Input kode gratis ongkir
- Status indicator (AKTIF/TIDAK AKTIF)
- Tips psikologi marketing
- Auto uppercase

---

## 🚀 Yang Masih Perlu Diimplementasikan

### ⏳ **1. Update SettingController**
Tambahkan validasi dan save untuk `free_shipping_code`

### ⏳ **2. Form Order dengan Dropdown Wilayah**
- Dropdown provinsi
- Dropdown kota (dynamic)
- Dropdown kecamatan (dynamic)
- Hitung ongkir otomatis

### ⏳ **3. AJAX untuk Hitung Ongkir**
- Real-time calculation
- Pilih kurir & service
- Update total otomatis

### ⏳ **4. Notifikasi Marketing di Halaman Order**
- Banner gratis ongkir (jika kode aktif)
- Countdown timer
- FOMO elements
- Social proof

### ⏳ **5. Validasi Kode Gratis Ongkir**
- Check kode valid
- Apply discount
- Update total

---

## 📊 Psikologi Marketing yang Digunakan

### **1. FOMO (Fear of Missing Out)**
```
🔥 BURUAN! Gratis Ongkir Terbatas!
⏰ Promo berakhir dalam 2 jam 34 menit
```

### **2. Eksklusivitas**
```
🎁 Kode Khusus untuk Customer Setia
✨ Hanya untuk 100 orang pertama!
```

### **3. Urgency**
```
⚡ Stok Terbatas! Hanya 5 slot tersisa
🚀 1.234 customer sudah pakai kode ini
```

### **4. Social Proof**
```
👥 Sarah dari Jakarta baru saja pakai kode ini
⭐ 4.9/5 rating dari 2.345 customer
```

---

## 🎨 Contoh Notifikasi Marketing

### **Banner di Halaman Order:**
```html
<div class="alert alert-success border-0 shadow-lg animate__animated animate__pulse">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h4 class="mb-2">
                🎉 SELAMAT! Kamu Beruntung!
            </h4>
            <p class="mb-2">
                Dapatkan <strong class="text-danger">GRATIS ONGKIR</strong> 
                dengan kode: <code class="bg-warning px-3 py-1 rounded">GRATISONGKIR2026</code>
            </p>
            <small class="text-muted">
                ⏰ Promo terbatas! Berakhir dalam <strong id="countdown">2:34:56</strong>
            </small>
        </div>
        <div class="col-md-4 text-end">
            <div class="badge bg-danger fs-6 px-3 py-2">
                🔥 HEMAT Rp 25.000
            </div>
        </div>
    </div>
</div>
```

### **Floating Banner (Sticky):**
```html
<div class="position-fixed bottom-0 start-0 end-0 bg-gradient p-3 shadow-lg" 
     style="z-index: 1000; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container">
        <div class="row align-items-center text-white">
            <div class="col-md-8">
                <h6 class="mb-0">
                    🎁 Pakai kode <strong>GRATISONGKIR2026</strong> untuk gratis ongkir!
                </h6>
            </div>
            <div class="col-md-4 text-end">
                <button class="btn btn-warning btn-sm" onclick="applyCode()">
                    <i class="fas fa-gift"></i> Pakai Sekarang
                </button>
            </div>
        </div>
    </div>
</div>
```

---

## 💡 Best Practices

### **Kode yang Efektif:**
```
✅ GRATISONGKIR2026
✅ FREESHIPJAN
✅ ONGKIR0
✅ HEMAT25K

❌ abc123 (tidak jelas)
❌ KODE1 (tidak menarik)
❌ FREESHIPPING2026JANUARY (terlalu panjang)
```

### **Timing:**
- **Hari Gajian:** Kode ekstra menarik
- **Weekend:** Promo weekend special
- **Hari Besar:** Ramadan, Lebaran, dll
- **Flash Sale:** 2-4 jam saja

### **Kombinasi dengan:**
- Countdown timer
- Limited stock indicator
- Recent orders notification
- Customer testimonials

---

## 🔧 Configuration

### **1. BinderByte API Key**
```env
BINDERBYTE_API_KEY=8e49f28e0f2f2cf56393c352613eec358e85fb7077ce6f7f453ebb826a7b1f6d
```

### **2. Admin Settings**
```
Admin → Settings → Website
→ Kode Gratis Ongkir: GRATISONGKIR2026
→ Simpan
```

---

## 📋 Next Steps

1. **Update SettingController** untuk save kode
2. **Buat form order** dengan dropdown wilayah
3. **Implementasi AJAX** hitung ongkir
4. **Tambahkan notifikasi marketing** di halaman order
5. **Testing** end-to-end

---

**Status:** 🟡 In Progress (40% Complete)  
**Created:** 2026-01-03  
**Priority:** High
