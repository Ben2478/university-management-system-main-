# Payment System Integration - Zananco BillMaster

## Overview
The Student Management System now includes a complete payment processing system integrated with Zananco Bank's BillMaster payment gateway. Students can pay tuition and fees securely through the gateway.

## Features
- ✅ Payment initiation and processing
- ✅ Zananco BillMaster gateway integration
- ✅ Payment status tracking (pending, processing, completed, failed)
- ✅ Webhook callback handling for payment verification
- ✅ Payment history per student
- ✅ Admin payment management dashboard
- ✅ Transaction reference tracking

## Setup Instructions

### 1. Configure Zananco Credentials
Add the following to your `.env` file:

```env
# Zananco Bank BillMaster Configuration
ZANANCO_BASE_URL=https://billmaster.zananco.com/api
ZANANCO_MERCHANT_ID=your_merchant_id_here
ZANANCO_API_KEY=your_api_key_here
ZANANCO_SANDBOX=true  # Set to false for production
```

**Where to get credentials:**
1. Register with Zananco Bank at https://www.zananco.com
2. Create a merchant account in the BillMaster dashboard
3. Generate API credentials from your merchant settings
4. Copy Merchant ID and API Key to `.env`

### 2. Database Setup
The migrations have already been run. The `payments` table is now in your database with the following fields:
- `id` - Payment ID
- `student_id` - Foreign key to students table
- `amount` - Payment amount in ZMW
- `description` - Payment description
- `status` - Payment status (pending, processing, completed, failed, cancelled)
- `transaction_id` - Zananco transaction ID
- `reference_number` - Internal reference number
- `payment_method` - Payment method (default: zananco_billmaster)
- `paid_at` - Timestamp when payment was confirmed
- `gateway_response` - JSON response from gateway
- `notes` - Internal notes

## Usage

### For Students - Making a Payment

1. **Navigate to Student Profile**
   - Go to `Students` → Select a student
   - Click the "Payments" tab or "Make Payment" button

2. **Initiate Payment**
   - Route: `GET /students/{student}/payments` (view payment history)
   - Route: `GET /payments/create?student={id}` (create new payment)
   - Enter amount and description
   - Click "Proceed to Payment"

3. **Complete Payment**
   - Student is redirected to Zananco BillMaster gateway
   - Enters payment details (card, mobile money, etc.)
   - Zananco processes the payment
   - Student is redirected back with confirmation

### For Admins - Managing Payments

1. **View All Payments**
   - Route: `GET /payments` 
   - See all system payments with filtering by status

2. **View Payment Details**
   - Route: `GET /payments/{payment}`
   - View transaction details and gateway response

3. **Verify a Payment**
   - Route: `POST /payments/{payment}/verify`
   - Manually verify a payment status with Zananco
   - Updates local payment record if verified as completed

## API Endpoints

### Payment Routes

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/payments` | List all payments (admin) |
| GET | `/payments/create` | Show payment creation form |
| POST | `/payments` | Store new payment |
| GET | `/payments/{payment}` | View payment details |
| POST | `/payments/{payment}/verify` | Verify payment with gateway |
| GET | `/students/{student}/payments` | View student payment history |
| POST | `/payments/callback` | Webhook for payment confirmation |

## Integration Flow

```
Student Initiates Payment
    ↓
PaymentController@store
    ├→ Creates Payment record (status: pending)
    ├→ Calls ZnancoGateway@initiatePayment
    └→ Redirects to Zananco payment page
         ↓
    Student completes payment at Zananco
         ↓
    Zananco sends webhook callback
         ↓
PaymentController@callback
    ├→ Verifies webhook signature
    ├→ Verifies payment with ZnancoGateway@verifyPayment
    └→ Updates Payment record (status: completed)
```

## Code Structure

### Models
- **Payment** (`app/Models/Payment.php`)
  - Relationships: `belongsTo(Student)`
  - Methods: `markAsCompleted()`, `markAsFailed()`, status checks

### Controllers
- **PaymentController** (`app/Http/Controllers/PaymentController.php`)
  - `index()` - List payments
  - `create()` - Show payment form
  - `store()` - Create payment
  - `show()` - View details
  - `verify()` - Manual verification
  - `callback()` - Webhook handler
  - `studentPayments()` - Student history

### Services
- **ZnancoGateway** (`app/Services/Payment/ZnancoGateway.php`)
  - `initiatePayment()` - Start payment request
  - `verifyPayment()` - Verify transaction
  - `verifyWebhookSignature()` - Validate callbacks

### Views
- `resources/views/payments/index.blade.php` - Payment list
- `resources/views/payments/create.blade.php` - Payment form
- `resources/views/payments/show.blade.php` - Payment details
- `resources/views/payments/student-history.blade.php` - Student history

## Testing

### Test Payment Initiation
```php
// In tinker or tests
$student = Student::find(1);
$gateway = new ZnancoGateway();

$result = $gateway->initiatePayment(
    amount: '150.00',
    description: 'Test Payment',
    customerName: $student->first_name . ' ' . $student->last_name,
    customerEmail: $student->email,
    phoneNumber: $student->phone
);

dd($result);
```

### Test Payment Verification
```php
$payment = Payment::find(1);
$gateway = new ZnancoGateway();

$verification = $gateway->verifyPayment($payment->transaction_id);
dd($verification);
```

## Error Handling

The system handles the following errors gracefully:
- **Network errors** - Failed API calls are logged with specific error messages
- **Invalid signatures** - Webhook callbacks with invalid signatures are rejected
- **Missing data** - Missing student or payment records return appropriate errors
- **Gateway failures** - Payment failures are tracked with gateway response

All errors are logged and can be reviewed in `storage/logs/laravel.log`.

## Security Considerations

1. **Signature Verification** - All Zananco callbacks are verified with HMAC-SHA256
2. **API Key Protection** - Never expose API keys in client-side code (stored in `.env`)
3. **HTTPS Only** - Payment URLs should only be used over HTTPS in production
4. **Amount Validation** - Server-side validation prevents amount manipulation
5. **Reference Numbers** - Unique reference tracking prevents duplicate payments

## Troubleshooting

### "Migration table not found"
- Run: `php artisan migrate`

### "Payment initiation failed"
- Check `.env` for correct Zananco credentials
- Ensure `ZANANCO_SANDBOX=true` for test mode
- Check network connectivity to gateway API

### "Invalid signature"
- Verify that `ZANANCO_API_KEY` matches your Zananco account
- Check that webhook is sending complete payload

### Payments showing as "Pending"
- Click "Verify" button on payment details page
- Check Zananco merchant dashboard for transaction status

## Next Steps

1. **Get Zananco credentials** from https://www.zananco.com
2. **Update .env** with your merchant ID and API key
3. **Test with sandbox** (ZANANCO_SANDBOX=true)
4. **Switch to production** (ZANANCO_SANDBOX=false) when ready
5. **Set up webhooks** in Zananco dashboard pointing to `/payments/callback`

## Support

For Zananco BillMaster API documentation, visit:
- https://billmaster.zananco.com/docs
- https://www.zananco.com/support

For issues with the Laravel integration, check the Laravel logs:
```bash
tail -f storage/logs/laravel.log
```
