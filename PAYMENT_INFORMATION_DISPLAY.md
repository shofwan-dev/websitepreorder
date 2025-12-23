# Payment Information Display - Order Detail Page

## 📋 Informasi yang Ditampilkan

Halaman detail order (`/my/orders/{id}`) sekarang menampilkan informasi lengkap tentang pembayaran iPaymu.

## 🎨 Layout Section

### **Payment Summary Card - Sidebar**

```
┌─────────────────────────────────────────┐
│  💳 Ringkasan Pembayaran                │
├─────────────────────────────────────────┤
│                                         │
│  Subtotal         Rp 200.000            │
│  Ongkir           Dihitung nanti        │
│  ──────────────────────────────         │
│  Total            Rp 200.000            │
│                                         │
│  Status Pembayaran                      │
│  [🔴 Belum Bayar]  atau                 │
│  [🟢 Lunas]                              │
│                                         │
│  Tanggal Pembayaran (if paid)           │
│  23 Des 2024, 12:00                     │
│                                         │
│  ──────────────────────────────────     │
│  📜 Informasi Pembayaran                │
│  ──────────────────────────────────     │
│                                         │
│  Transaction ID                         │
│  c469ae67-842e-467d-a73d-f58181890e11   │
│                                         │
│  Session ID (if different)              │
│  20c1f44e-d245-4271-abfb-45f0a93805fe   │
│                                         │
│  Merchant Ref ID                        │
│  ORDER-1                                │
│                                         │
│  Berlaku Hingga                         │
│  24 Des 2024, 12:00 ✓                   │
│  (or expired: 23 Des 2024, 11:00 ⚠️)    │
│                                         │
│  ──────────────────────────────────     │
│                                         │
│  ℹ️ Link pembayaran sudah dibuat.       │
│     Klik tombol di bawah untuk          │
│     melanjutkan pembayaran.             │
│                                         │
│  [🔗 Lanjutkan Pembayaran]              │
│                                         │
│  ⚠️ Silakan lakukan pembayaran untuk    │
│     memproses pesanan Anda.             │
│                                         │
└─────────────────────────────────────────┘
```

## 📊 Conditional Display Logic

### **1. When Payment NOT Initiated**
```blade
Payment Information Section: HIDDEN

Action Section:
[💳 Bayar Sekarang]  ← Button to create payment
```

### **2. When Payment Link Created (Pending)**
```blade
Payment Information Section: VISIBLE
├─ Transaction ID: ✅ Shown (SessionID)
├─ Session ID: ✅ Shown (if different from TrxID)
├─ Merchant Ref ID: ✅ Shown (ORDER-{id})
└─ Berlaku Hingga: ✅ Shown (if available)

Action Section:
[🔗 Lanjutkan Pembayaran]  ← Link to iPaymu page
```

### **3. When Payment Successful**
```blade
Payment Information Section: VISIBLE
├─ Transaction ID: ✅ Shown
├─ Session ID: ✅ Shown
├─ Merchant Ref ID: ✅ Shown
├─ Berlaku Hingga: ✅ Shown (green if not expired)
└─ Tanggal Pembayaran: ✅ Shown

Action Section: HIDDEN

Success Message:
✅ Pembayaran telah diterima. Pesanan Anda sedang diproses.
```

### **4. When Payment Expired**
```blade
Payment Information Section: VISIBLE
├─ Transaction ID: ✅ Shown
├─ Session ID: ✅ Shown
├─ Merchant Ref ID: ✅ Shown
└─ Berlaku Hingga: ⚠️ Shown in RED with warning icon

Action Section:
Info message: Link pembayaran telah expired
(No button - needs admin intervention)
```

## 🎨 Styling Details

### **Transaction ID Display**
```html
<code class="small">c469ae67-842e-467d-a73d-f58181890e11</code>
```
- Monospace font
- Smaller text size
- Light background
- Easy to copy

### **Expiry Date Display**
```html
<!-- Active/Valid -->
<strong class="small text-success">
    24 Des 2024, 12:00
</strong>

<!-- Expired -->
<strong class="small text-danger">
    23 Des 2024, 11:00
    <i class="fas fa-exclamation-circle ms-1"></i>
</strong>
```

### **Section Header**
```html
<h6 class="text-muted small mb-2">
    <i class="fas fa-receipt me-1"></i> Informasi Pembayaran
</h6>
```

## 📱 Responsive Behavior

### **Desktop (col-lg-4)**
Payment Summary card di sidebar kanan

### **Mobile (col-12)**
Payment Summary card full width di bawah product info

## 🔍 Data Source

### **Order Model Fields**
```php
$order->ipaymu_transaction_id  // Transaction/Session ID dari iPaymu
$order->ipaymu_session_id      // Session ID (if available)
$order->id                     // Merchant Reference ID
$order->payment_expired_at     // Expiry datetime
$order->paid_at                // Payment completion time
$order->payment_status         // paid/pending/expired/refunded
```

## 📋 Display Rules

1. **Show Payment Information Section**
   - IF `ipaymu_transaction_id` OR `ipaymu_session_id` exists
   - THEN show the section

2. **Show Transaction ID**
   - ALWAYS if exists

3. **Show Session ID**
   - ONLY if different from Transaction ID
   - (Avoid duplicate display)

4. **Show Merchant Ref ID**
   - ALWAYS (Format: ORDER-{id})

5. **Show Expiry Date**
   - IF `payment_expired_at` exists
   - Color: GREEN if future, RED if past

6. **Show Payment Date**
   - ONLY if `paid_at` exists
   - (Payment successful)

## 🎯 User Benefits

### **For Customer**
✅ **Transparency** - See exact transaction IDs
✅ **Reference** - Can use IDs when contacting support
✅ **Status** - Clear expiry date indication
✅ **Tracking** - All payment info in one place

### **For Support**
✅ **Quick Lookup** - Customer provides Transaction ID
✅ **Verification** - Confirm Merchant Ref matches order
✅ **Troubleshooting** - Check if payment expired
✅ **Resolution** - All data needed for investigation

## 📞 Support Scenario

**Customer:** "Saya sudah bayar tapi belum masuk"

**Support:** "Boleh berikan Transaction ID atau Session ID nya?"

**Customer:** "c469ae67-842e-467d-a73d-f58181890e11"

**Support:** *(Searches in iPaymu dashboard or logs)*
           → Finds transaction
           → Checks status
           → Resolves issue

## 🔐 Security Note

- Transaction IDs are **SAFE to display** to customer
- They are payment references, not sensitive credentials
- Customer may need them for support/disputes
- DO NOT display: API Keys, Signatures, etc.

## ✅ Implementation Checklist

- [x] Add Payment Information section
- [x] Display Transaction ID
- [x] Display Session ID (conditional)
- [x] Display Merchant Ref ID
- [x] Display Expiry Date with color coding
- [x] Display Payment Date (if paid)
- [x] Remove duplicate expiry display
- [x] Conditional section visibility
- [x] Responsive layout
- [x] Copy-friendly formatting

---

## 🎊 Result

Customer sekarang bisa melihat:
1. ✅ **Transaction ID** - Untuk referensi pembayaran
2. ✅ **Session ID** - ID sesi pembayaran (jika ada)
3. ✅ **Merchant Ref ID** - Order-{id} untuk tracking
4. ✅ **Expiry Date** - Kapan link pembayaran kadaluarsa
5. ✅ **Payment Date** - Kapan pembayaran selesai (jika sudah lunas)

**Clean, informative, and professional!** 🎨
