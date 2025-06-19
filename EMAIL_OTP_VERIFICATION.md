# Email OTP Verification System

## Overview
This Laravel application now includes a complete Email OTP (One-Time Password) verification system for users who register manually (without Google signup). Users must verify their email address before accessing protected areas of the application.

## Features Implemented

### 1. Database Schema
- Added OTP fields to users table:
  - `email_otp` (string, 6 digits): The OTP code
  - `email_otp_expires_at` (timestamp): When the OTP expires (10 minutes)
  - `is_verified` (boolean): Whether the user has verified their email

### 2. User Model Methods
- `generateEmailOtp()`: Generate and store a 6-digit OTP with 10-minute expiry
- `verifyEmailOtp($otp)`: Verify the provided OTP and mark user as verified
- `clearEmailOtp()`: Clear OTP data from database
- `hasVerifiedEmail()`: Check if user has verified their email
- `hasValidOtp()`: Check if current OTP is still valid (not expired)

### 3. AuthController Methods
- `register()`: Modified to send OTP email instead of auto-login
- `login()`: Modified to check email verification status
- `showOtpVerificationForm()`: Display OTP input form
- `verifyOtp()`: Process OTP verification
- `resendOtp()`: Send new OTP to user's email

### 4. Middleware
- `EnsureEmailIsVerified`: Redirects unverified users to OTP verification page
- Applied to protected routes like dashboard, profile, etc.

### 5. Email Integration
- Uses existing `OTPMail` class to send verification codes
- Professional email template with 6-digit code
- Auto-includes user's first name for personalization

## User Flow

### Registration Flow:
1. User fills registration form
2. Account is created but marked as **unverified**
3. OTP email is sent automatically
4. User is redirected to OTP verification page
5. User enters 6-digit code from email
6. Email is verified and user is logged in
7. User can now access protected areas

### Login Flow:
1. User enters login credentials
2. If account is unverified, user is redirected to OTP verification
3. New OTP is sent if previous one expired
4. User verifies email and gains access

## Routes

### OTP Verification Routes:
- `GET /verify-email`: Show OTP verification form
- `POST /verify-email`: Verify submitted OTP
- `POST /resend-otp`: Send new OTP code

### Protected Routes (require email verification):
- `/dashboard`
- `/profile` and all profile-related routes
- Other authenticated routes as needed

## Security Features

### OTP Security:
- **6-digit random codes**: Difficult to guess
- **10-minute expiry**: Short window reduces risk
- **One-time use**: OTP is cleared after successful verification
- **Rate limiting**: Prevents spam OTP requests

### Bypass Rules:
- **Google users**: Auto-verified (no OTP required)
- **Already verified users**: Skip verification check
- **OTP routes**: Accessible to complete verification

## Configuration

### Email Settings:
Configure your email settings in `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourapp.com
MAIL_FROM_NAME="Your App Name"
```

### OTP Settings:
- **Code length**: 6 digits
- **Expiry time**: 10 minutes
- **Resend cooldown**: Only when current OTP expires

## Database Migration
Run the migration to add OTP fields:
```bash
php artisan migrate
```

## Testing the System

### Test Registration:
1. Go to `/register`
2. Fill form with valid email
3. Submit registration
4. Check email for 6-digit code
5. Enter code on verification page
6. Should be redirected to dashboard

### Test Login with Unverified Account:
1. Create account but don't verify
2. Try to login
3. Should be redirected to OTP verification
4. New OTP should be sent
5. Verify and access dashboard

### Test Protected Routes:
1. Try accessing `/dashboard` or `/profile` while unverified
2. Should be redirected to OTP verification
3. After verification, should access requested page

## Error Handling
- Invalid OTP: Clear error message
- Expired OTP: Option to resend
- Missing user session: Redirect to registration
- Email sending failures: Graceful error handling

## User Experience Features
- **Auto-submit**: Form submits when 6 digits entered
- **Auto-focus**: Cursor ready in OTP input
- **Visual feedback**: Success/error messages
- **Resend option**: When OTP expires
- **Help text**: Tips for finding email
- **Mobile-friendly**: Responsive design

The OTP verification system is now fully functional and provides secure email verification for manual registrations!
