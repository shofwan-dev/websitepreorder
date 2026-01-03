# iPaymu Payment Flow Diagram

## Flow 1: User Payment Process

```
┌─────────────┐
│    User     │
│  Dashboard  │
└──────┬──────┘
       │
       │ Click "Create Order"
       ▼
┌─────────────┐
│ Create Order│
│    Form     │
└──────┬──────┘
       │
       │ Submit Form
       ▼
┌─────────────────────┐
│ OrderController     │
│ store()             │
│ - Create Order      │
│ - Status: pending   │
│ - Payment: pending  │
└──────┬──────────────┘
       │
       │ Redirect to
       ▼
┌──────────────────────────┐
│  Order Detail Page       │
│  - Show Product Info     │
│  - Show Shipping Info    │
│  ┌───────────────────┐   │
│  │ Payment Summary   │   │
│  │ [Bayar Sekarang]  │◄──┼── Button Click
│  └───────────────────┘   │
└──────┬───────────────────┘
       │
       │ POST /my/orders/{id}/pay
       ▼
┌──────────────────────────┐
│ OrderController          │
│ processPayment()         │
│ 1. Check order status    │
│ 2. Prepare order data    │
│ 3. Call IPaymuService    │
└──────┬───────────────────┘
       │
       ▼
┌──────────────────────────┐
│   IPaymuService          │
│   createPayment()        │
│   - Generate signature   │
│   - POST to iPaymu API   │
│   - Get payment URL      │
└──────┬───────────────────┘
       │
       │ Return payment data
       ▼
┌──────────────────────────┐
│ Update Order             │
│ - ipaymu_transaction_id  │
│ - ipaymu_payment_url     │
│ - ipaymu_session_id      │
│ - payment_expired_at     │
└──────┬───────────────────┘
       │
       │ Redirect to
       ▼
┌──────────────────────────┐
│  iPaymu Payment Page     │
│  - Select Payment Method │
│  - Enter Payment Details │
│  - Complete Payment      │
└──────┬──────────┬────────┘
       │          │
Success│          │Cancel
       │          │
       ▼          ▼
  ┌────────┐  ┌────────┐
  │ Return │  │ Cancel │
  │  URL   │  │  URL   │
  └────┬───┘  └───┬────┘
       │          │
       └────┬─────┘
            │
            ▼
     ┌─────────────┐
     │ Order Detail│
     │    Page     │
     └─────────────┘
```

## Flow 2: iPaymu Callback Process

```
┌─────────────────┐
│  iPaymu Server  │
└────────┬────────┘
         │
         │ POST /ipaymu/callback
         │ {trx_id, status, ...}
         ▼
┌─────────────────────────┐
│ IPaymuCallbackController│
│ callback()              │
└────────┬────────────────┘
         │
         │ 1. Log callback data
         ▼
┌─────────────────────────┐
│   IPaymuService         │
│   checkTransaction()    │
│   - Verify transaction  │
│   - Get latest status   │
└────────┬────────────────┘
         │
         │ 2. Find order by trx_id
         ▼
┌─────────────────────────┐
│   Order Model           │
│   WHERE                 │
│   ipaymu_transaction_id │
└────────┬────────────────┘
         │
         │ 3. Update based on status
         ▼
    ┌────┴────┐
    │ Status? │
    └────┬────┘
         │
    ┌────┼────┬────────┐
    │    │    │        │
   1/6/7 │    │       Other
    │    │    │        │
    v    v    v        v
  ┌───┐┌───┐┌───┐  ┌─────┐
  │ 1 ││ 6 ││ 7 │  │Skip │
  │OK ││REF││EXP│  │     │
  └─┬─┘└─┬─┘└─┬─┘  └─────┘
    │    │    │
    ▼    ▼    ▼
┌─────────────────────────┐
│ Update Order            │
│ - payment_status        │
│ - paid_at (if success)  │
└────────┬────────────────┘
         │
         │ 4. Save changes
         ▼
┌─────────────────────────┐
│ Return JSON Response    │
│ {status: success}       │
└─────────────────────────┘
```

## Status Mapping

```
┌──────────────────────────────────────┐
│      iPaymu Status Codes             │
├──────────┬───────────────────────────┤
│  Code    │  Meaning                  │
├──────────┼───────────────────────────┤
│   -2     │  Expired                  │
│    0     │  Pending                  │
│    1     │  Berhasil (Paid) ✓        │
│    2     │  Batal                    │
│    3     │  Refund                   │
│    4     │  Error                    │
│    5     │  Gagal                    │
│    6     │  Berhasil - Unsettled ✓   │
│    7     │  Escrow ✓                 │
└──────────┴───────────────────────────┘

✓ = Considered as "Paid"
```

## Database Schema

```
┌─────────────────────────────────────────┐
│           orders Table                  │
├─────────────────────────────────────────┤
│ id                     : bigint         │
│ user_id                : bigint         │
│ product_id             : bigint         │
│ transaction_id         : string         │
│ ...                                     │
│                                         │
│ ┌─ Payment Related ──────────────────┐  │
│ │ payment_status      : enum         │  │
│ │ ipaymu_transaction_id : string     │  │
│ │ ipaymu_payment_url  : text         │  │
│ │ ipaymu_session_id   : string       │  │
│ │ payment_expired_at  : timestamp    │  │
│ │ paid_at             : timestamp    │  │
│ └────────────────────────────────────┘  │
│                                         │
│ ┌─ Customer Info ─────────────────────┐ │
│ │ customer_name       : string       │ │
│ │ customer_email      : string       │ │
│ │ customer_phone      : string       │ │
│ │ customer_address    : text         │ │
│ │ customer_city       : string       │ │
│ └────────────────────────────────────┘ │
└─────────────────────────────────────────┘
```

## API Signature Generation

```
┌───────────────────────────────────────────┐
│  Signature Generation Process             │
└───────────────────────────────────────────┘
         │
         ▼
┌───────────────────────────────────────────┐
│ 1. Prepare Body (JSON)                    │
│    {product: [...], qty: [...], ...}      │
└──────────────┬────────────────────────────┘
               │
               ▼
┌───────────────────────────────────────────┐
│ 2. Hash Body                              │
│    bodyHash = sha256(jsonBody)            │
│    bodyHash = lowercase(bodyHash)         │
└──────────────┬────────────────────────────┘
               │
               ▼
┌───────────────────────────────────────────┐
│ 3. Create String to Sign                  │
│    stringToSign = METHOD + ':' + VA + ':' │
│                   + bodyHash + ':' +      │
│                   + API_KEY               │
│    Example:                               │
│    POST:1179000899:abc123...:apikey123    │
└──────────────┬────────────────────────────┘
               │
               ▼
┌───────────────────────────────────────────┐
│ 4. Generate HMAC SHA256                   │
│    signature = hmac_sha256(               │
│        stringToSign,                      │
│        API_KEY                            │
│    )                                      │
└──────────────┬────────────────────────────┘
               │
               ▼
┌───────────────────────────────────────────┐
│ 5. Use in Headers                         │
│    signature: {generated_signature}       │
│    va: {VA_NUMBER}                        │
│    timestamp: {YmdHis}                    │
└───────────────────────────────────────────┘
```

## Testing Workflow

```
┌─────────────────────────────────────────┐
│         Local Testing                   │
└─────────────────────────────────────────┘

Step 1: Setup Environment
┌─────────────────────┐
│ .env Configuration  │
│ - IPAYMU_VA         │
│ - IPAYMU_API_KEY    │
│ - IPAYMU_ENVIRONMENT│
└──────────┬──────────┘
           │
           ▼
Step 2: Test Connection
┌─────────────────────┐
│ Admin Panel         │
│ /admin/settings/    │
│ payment/test        │
└──────────┬──────────┘
           │
           ▼
Step 3: Create Test Order
┌─────────────────────┐
│ Login as User       │
│ Create Order        │
│ Click "Bayar"       │
└──────────┬──────────┘
           │
           ▼
Step 4: Check Logs
┌─────────────────────┐
│ storage/logs/       │
│ laravel.log         │
│ - API Request       │
│ - API Response      │
└──────────┬──────────┘
           │
           ▼
Step 5: Simulate Callback (Optional)
┌─────────────────────┐
│ Use Postman/cURL    │
│ POST /ipaymu/       │
│ callback            │
└─────────────────────┘
```

## Error Handling

```
┌──────────────────────────────────────────┐
│        Error Scenarios                   │
└──────────────────────────────────────────┘

API Call Failed
├─ Network Error
│  └─ Retry / Show error message
│
├─ 401 Unauthorized
│  └─ Check VA & API Key
│  └─ Check signature generation
│
├─ 400 Bad Request
│  └─ Validate request body
│  └─ Check required fields
│
└─ 500 Server Error
   └─ Contact iPaymu support
   └─ Check server status

Payment Process Failed
├─ Order already paid
│  └─ Show info message
│
├─ Order cancelled
│  └─ Show error message
│
└─ Payment link creation failed
   └─ Log error
   └─ Show user-friendly message
```
