# iPaymu Integration Testing Checklist

## ✅ Pre-Integration Checklist

- [ ] Environment variables configured in `.env`
  - [ ] `IPAYMU_VA` 
  - [ ] `IPAYMU_API_KEY`
  - [ ] `IPAYMU_ENVIRONMENT=sandbox`
  
- [ ] Database migrations run
  - [ ] Orders table has iPaymu fields
  - [ ] `ipaymu_transaction_id`
  - [ ] `ipaymu_payment_url`
  - [ ] `ipaymu_session_id`
  - [ ] `payment_expired_at`

- [ ] Routes registered
  - [ ] `POST /my/orders/{order}/pay`
  - [ ] `POST /ipaymu/callback`
  - [ ] `GET /ipaymu/return`
  - [ ] `GET /ipaymu/cancel`

- [ ] CSRF exception added
  - [ ] `ipaymu/callback` in `VerifyCsrfToken.php`

## ✅ Unit Testing

### 1. IPaymuService Tests

#### Test: Generate Signature
```php
// Test signature generation
$service = new IPaymuService();
$body = ['test' => 'data'];
$signature = $service->generateSignature($body);
// Verify signature is not empty
// Verify signature format
```
**Expected**: ✓ Signature generated successfully

#### Test: Get Timestamp
```php
$service = new IPaymuService();
$timestamp = $service->getTimestamp();
// Format: YmdHis (e.g., 20231209155701)
```
**Expected**: ✓ Timestamp in correct format

#### Test: Connection Test
```php
$service = new IPaymuService();
$result = $service->testConnection();
```
**Expected**: ✓ Connection successful (atau response dari iPaymu)

### 2. Order Controller Tests

#### Test: Create Order
- [ ] Navigate to `/my/orders/create`
- [ ] Fill form with valid data
- [ ] Submit form
- [ ] Check order created in database
- [ ] Verify redirect to order detail page

**Expected**: ✓ Order created with `payment_status = 'pending'`

#### Test: Process Payment (Not Authenticated)
- [ ] Access `/my/orders/{id}/pay` without login
- [ ] Should redirect to login page

**Expected**: ✓ Redirected to login

#### Test: Process Payment (Different User)
- [ ] Login as User A
- [ ] Try to pay order from User B
- [ ] Should get 403 error

**Expected**: ✓ Access denied (403)

#### Test: Process Payment (Already Paid)
- [ ] Set order `payment_status = 'paid'`
- [ ] Try to process payment
- [ ] Should show info message

**Expected**: ✓ "Order ini sudah dibayar"

#### Test: Process Payment (Cancelled Order)
- [ ] Set order `status = 'cancelled'`
- [ ] Try to process payment
- [ ] Should show error message

**Expected**: ✓ "Order ini sudah dibatalkan"

## ✅ Integration Testing

### 1. End-to-End Payment Flow

#### Scenario: Successful Payment Creation
1. [ ] Login as regular user
2. [ ] Create new order
   - Product: Any active product
   - Quantity: 1
   - Customer info: Valid data
3. [ ] Click "Bayar Sekarang"
4. [ ] Check logs (`storage/logs/laravel.log`)
   - [ ] Request to iPaymu logged
   - [ ] Response from iPaymu logged
5. [ ] Verify database update
   - [ ] `ipaymu_transaction_id` populated
   - [ ] `ipaymu_payment_url` populated
   - [ ] `ipaymu_session_id` populated (if available)
   - [ ] `payment_expired_at` populated (if available)
6. [ ] User redirected to iPaymu payment page

**Expected**: ✓ All steps successful

#### Scenario: View Existing Payment Link
1. [ ] Order with existing `ipaymu_payment_url`
2. [ ] View order detail page
3. [ ] Check UI displays:
   - [ ] "Link pembayaran sudah dibuat" message
   - [ ] Payment expiry date (if available)
   - [ ] "Lanjutkan Pembayaran" button

**Expected**: ✓ Payment link button displayed

### 2. Callback Handling

#### Scenario: Successful Payment Callback (Status 1)
1. [ ] Use Postman/cURL to simulate callback
```bash
POST http://localhost/ipaymu/callback
Content-Type: application/json

{
  "trx_id": "test-transaction-id",
  "status": "1"
}
```
2. [ ] Check logs for callback processing
3. [ ] Verify order status updated:
   - [ ] `payment_status = 'paid'`
   - [ ] `paid_at` timestamp set

**Expected**: ✓ Order marked as paid

#### Scenario: Refund Callback (Status 6)
1. [ ] Simulate callback with status 6
2. [ ] Verify order status:
   - [ ] `payment_status = 'refunded'`

**Expected**: ✓ Order marked as refunded

#### Scenario: Expired Callback (Status 7)
1. [ ] Simulate callback with status 7
2. [ ] Verify order status:
   - [ ] `payment_status = 'expired'`

**Expected**: ✓ Order marked as expired

#### Scenario: Invalid Transaction ID
1. [ ] Simulate callback with non-existent `trx_id`
2. [ ] Check response: 404 error
3. [ ] Check logs for error message

**Expected**: ✓ "Order not found" error logged

### 3. Return & Cancel URLs

#### Scenario: Return URL
1. [ ] Access return URL manually:
   `GET /ipaymu/return?trx_id=test-transaction-id`
2. [ ] Verify redirect to order detail page
3. [ ] Check success message displayed

**Expected**: ✓ Redirect with message

#### Scenario: Cancel URL
1. [ ] Access cancel URL:
   `GET /ipaymu/cancel`
2. [ ] Verify redirect to orders list
3. [ ] Check warning message displayed

**Expected**: ✓ Redirect with warning

## ✅ UI/UX Testing

### Order Detail Page

#### When Payment Status = 'pending'
- [ ] "Bayar Sekarang" button visible
- [ ] Payment summary card visible
- [ ] Status badge shows "Belum Bayar" (red)
- [ ] Alert message: "Silakan lakukan pembayaran..."

#### When Payment Status = 'paid'
- [ ] No payment button
- [ ] Status badge shows "Lunas" (green)
- [ ] Success message: "Pembayaran telah diterima..."

#### When Order Status = 'cancelled'
- [ ] No payment button
- [ ] Status badge shows "Dibatalkan"

#### When Payment URL Exists
- [ ] "Lanjutkan Pembayaran" button visible
- [ ] Payment expiry date visible (if available)
- [ ] Info message about existing payment link

### Flash Messages
- [ ] Success messages display correctly
- [ ] Error messages display correctly
- [ ] Info messages display correctly
- [ ] Warning messages display correctly

## ✅ Security Testing

### CSRF Protection
- [ ] Callback endpoint excluded from CSRF
- [ ] Other payment routes protected by CSRF

### Authorization
- [ ] Users can only pay their own orders
- [ ] Users cannot pay other users' orders
- [ ] Admin cannot interfere with user payment process

### Data Validation
- [ ] Order data validated before sending to iPaymu
- [ ] Callback data validated before processing
- [ ] Signature validation (if implemented)

## ✅ Error Handling Testing

### API Errors

#### Network Error
1. [ ] Disconnect internet
2. [ ] Try to create payment
3. [ ] Check error message displayed
4. [ ] Check error logged

**Expected**: ✓ User-friendly error message

#### Invalid Credentials (401)
1. [ ] Use wrong API key
2. [ ] Try to create payment
3. [ ] Check error response
4. [ ] Verify error logged

**Expected**: ✓ "Unauthorized" error handled

#### Server Error (500)
1. [ ] Simulate 500 error (if possible)
2. [ ] Check error handling
3. [ ] Verify fallback behavior

**Expected**: ✓ Graceful error handling

### Application Errors

#### Missing Customer Email
1. [ ] Create order without customer_email
2. [ ] Try to process payment
3. [ ] Should use user's email as fallback

**Expected**: ✓ Uses `Auth::user()->email`

#### Product Not Found
1. [ ] Delete product
2. [ ] Try to pay existing order with deleted product
3. [ ] Check error handling

**Expected**: ✓ Error handled gracefully

## ✅ Performance Testing

### Response Time
- [ ] Payment creation < 5 seconds
- [ ] Callback processing < 2 seconds
- [ ] Page load < 3 seconds

### Load Testing
- [ ] Multiple simultaneous payment creations
- [ ] Multiple simultaneous callbacks
- [ ] No race conditions

## ✅ Additional API Methods Testing

### Check Balance
```php
$service = new IPaymuService();
$result = $service->checkBalance();
```
- [ ] Returns merchant balance
- [ ] Status 200 OK
- [ ] Data structure correct

### Transaction History
```php
$service = new IPaymuService();
$result = $service->getHistoryTransaction([
    'startdate' => '2024-01-01',
    'enddate' => '2024-12-31',
    'page' => 1,
    'limit' => 20
]);
```
- [ ] Returns transaction list
- [ ] Pagination works
- [ ] Date filtering works

### Calculate Shipping (COD)
```php
$service = new IPaymuService();
$result = $service->calculateShipping([
    'destination_area_id' => '17473',
    'pickup_area_id' => '17473',
    'weight' => 1,
    'amount' => 100000
]);
```
- [ ] Returns shipping cost
- [ ] Calculation correct

### Track Package (COD)
```php
$service = new IPaymuService();
$result = $service->trackPackage('AWB123', 'TRX123');
```
- [ ] Returns tracking info
- [ ] AWB or transaction_id required

## ✅ Log Verification

### Check Logs For:
- [ ] All API requests logged
- [ ] All API responses logged
- [ ] Errors properly logged with stack trace
- [ ] Callback events logged
- [ ] Payment status changes logged

### Log File Location
```
storage/logs/laravel.log
```

## ✅ Production Readiness Checklist

Before deploying to production:

- [ ] Change `IPAYMU_ENVIRONMENT=production`
- [ ] Update to production VA
- [ ] Update to production API Key
- [ ] SSL/HTTPS enabled
- [ ] Callback URL registered in iPaymu dashboard
- [ ] Test with small amount first
- [ ] Monitor logs for first few transactions
- [ ] Setup error alerting
- [ ] Backup database before deployment
- [ ] Have rollback plan ready

## 📊 Test Results Summary

| Test Category | Total Tests | Passed | Failed | Skipped |
|--------------|-------------|--------|--------|---------|
| Unit Tests | | | | |
| Integration Tests | | | | |
| UI/UX Tests | | | | |
| Security Tests | | | | |
| Error Handling | | | | |
| Performance | | | | |
| API Methods | | | | |
| **TOTAL** | | | | |

## 📝 Notes

Add any observations, issues, or improvements here:

```
Date: ___________
Tester: ___________

Issues Found:
1. 
2. 
3. 

Recommendations:
1. 
2. 
3. 
```

---

## Quick Test Command Reference

```bash
# View logs in real-time
tail -f storage/logs/laravel.log

# Clear logs
echo "" > storage/logs/laravel.log

# Test callback manually
curl -X POST http://localhost/ipaymu/callback \
  -H "Content-Type: application/json" \
  -d '{"trx_id":"test-123","status":"1"}'

# Check routes
php artisan route:list | grep ipaymu
php artisan route:list | grep orders

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```
