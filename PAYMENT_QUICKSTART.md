# Payment Integration - Quick Setup Guide

## What's New
Your Student Management System now has **full payment processing** integrated with **Zananco Bank's BillMaster**. Students can pay tuition online securely.

## Quick Start (5 minutes)

### Step 1: Get Zananco Credentials
1. Visit https://www.zananco.com
2. Register/Login to merchant dashboard
3. Create or select a merchant account
4. Go to Settings → API Keys
5. Copy: **Merchant ID** and **API Key**

### Step 2: Update .env
```env
ZANANCO_MERCHANT_ID=your_merchant_id
ZANANCO_API_KEY=your_api_key
ZANANCO_SANDBOX=true
```

### Step 3: Configure Webhook in Zananco Dashboard
- Webhook URL: `https://yourdomain.com/payments/callback`
- Method: POST
- Events: payment.completed, payment.failed

### Step 4: Test It
1. Go to Students
2. Click on any student
3. Click "Make Payment" button
4. Enter amount (e.g., 150.00)
5. Click "Proceed to Payment"
6. You'll see Zananco BillMaster payment form (sandbox mode)

## What Was Added

### New Routes
- `GET /payments` - View all payments
- `GET /payments/create` - Create new payment
- `POST /payments` - Submit payment
- `GET /payments/{payment}` - View payment details
- `POST /payments/{payment}/verify` - Verify with gateway
- `GET /students/{student}/payments` - Student payment history
- `POST /payments/callback` - Webhook from Zananco

### New Database Table
**payments** table with fields:
- student_id, amount, status, transaction_id, reference_number, paid_at, gateway_response

### New Files Created
```
app/Models/Payment.php
app/Http/Controllers/PaymentController.php
app/Services/Payment/ZnancoGateway.php
resources/views/payments/
  ├── index.blade.php
  ├── create.blade.php
  ├── show.blade.php
  └── student-history.blade.php
PAYMENTS_SETUP.md (detailed docs)
```

## Usage Examples

### Admin - View All Payments
- Navigate to Payments menu
- See status of all student payments
- Click on any payment to view details

### Admin - Verify a Payment
- Open payment details
- Click "Verify" button (if status not completed)
- System checks with Zananco and updates status

### Student - Make Payment
1. Go to your student profile
2. Click "Payments" tab
3. Click "New Payment"
4. Enter amount and description
5. Click "Proceed to Payment"
6. Complete payment on Zananco gateway

### Student - View Payment History
- Go to Students → Select Student
- Click "Payments" tab
- See all past and pending payments
- See payment summary (total paid, pending)

## Payment Statuses

| Status | Meaning |
|--------|---------|
| **pending** | Payment created, awaiting customer action |
| **processing** | Customer sent to gateway, processing |
| **completed** | Payment successful, money received |
| **failed** | Payment declined or customer cancelled |
| **cancelled** | Manually cancelled by admin |

## Testing in Sandbox Mode

With `ZANANCO_SANDBOX=true`, you can test using:
- **Test Card**: 4111 1111 1111 1111
- **Any future date** for expiry
- **Any CVC** (3 digits)

## Going Live (Production)

When ready to accept real payments:
1. Change `.env`: `ZANANCO_SANDBOX=false`
2. Update webhook URL to production domain
3. Test with real transaction
4. Monitor payments in Zananco dashboard

## Common Issues & Fixes

### "Payment table not found"
```bash
php artisan migrate
```

### "Failed to initiate payment"
- Check `.env` for correct credentials
- Check network connectivity
- See PAYMENTS_SETUP.md for detailed troubleshooting

### Payment showing as "Pending" after completion
- Click "Verify" button on payment details
- Or wait for automatic webhook callback

## File Locations

| Component | File |
|-----------|------|
| Model | `app/Models/Payment.php` |
| Controller | `app/Http/Controllers/PaymentController.php` |
| Gateway | `app/Services/Payment/ZnancoGateway.php` |
| Routes | `routes/web.php` |
| Config | `config/services.php` |
| Views | `resources/views/payments/` |
| Docs | `PAYMENTS_SETUP.md` (detailed) |

## Next Step
Open PAYMENTS_SETUP.md for complete documentation and troubleshooting guide.

Need help? Check the logs:
```bash
tail -f storage/logs/laravel.log
```
