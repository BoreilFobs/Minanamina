# Referral System - Complete Documentation

## Overview
The referral system allows users to earn bonuses by inviting friends to join the platform. When someone registers using a referral code, the referrer earns pieces automatically.

## Features Implemented

### 1. **User Features**
- ✅ Unique referral code for each user
- ✅ Referral dashboard (`/referrals`)
- ✅ Copy referral code and link
- ✅ View referred users list
- ✅ Track referral earnings
- ✅ Registration with referral code support

### 2. **Admin Features**
- ✅ Referral settings dashboard (`/admin/referrals`)
- ✅ Update referral bonus amount (default: 500 pieces)
- ✅ Enable/disable referral system
- ✅ View global referral statistics
- ✅ Top referrers leaderboard
- ✅ Recent referrals tracking

### 3. **Technical Implementation**

#### Database Tables
- `referral_settings` - Stores configurable settings (bonus amount, enabled/disabled)
- `referrals` - Tracks all referral transactions
- `users` table updated with:
  - `referral_code` - Unique code for each user
  - `referred_by` - Foreign key to referrer
  - `referral_earnings` - Total pieces earned from referrals
  - `total_referrals` - Count of successful referrals

#### Models
- `ReferralSetting` - Manages system settings with type casting
- `Referral` - Tracks referral transactions
- `User` - Extended with referral methods

#### Services
- `ReferralService` - Handles all referral logic:
  - Process new referrals
  - Award bonuses
  - Validate codes
  - Generate statistics

#### Controllers
- `ReferralController` - User-facing referral dashboard
- `Admin\ReferralSettingsController` - Admin management

## How It Works

### For Users
1. **Get Your Code**
   - Navigate to `/referrals`
   - Your unique code is displayed prominently
   - Copy code or full registration link

2. **Share Your Code**
   - Send to friends via social media, WhatsApp, etc.
   - Share the registration link: `/register?ref=YOUR_CODE`

3. **Earn Bonuses**
   - When someone registers with your code, you earn pieces
   - Default: 500 pieces per referral
   - Bonus is credited automatically

### For Admins
1. **Access Settings**
   - Navigate to `/admin/referrals`
   - View global statistics

2. **Update Bonus Amount**
   - Change the pieces awarded per referral
   - Range: 0 - 10,000 pieces

3. **Toggle System**
   - Enable/disable referral system
   - When disabled, codes cannot be used during registration

4. **Monitor Activity**
   - View recent referrals
   - See top referrers leaderboard
   - Track total bonuses paid

## Registration Flow

When a user registers with a referral code:

1. **Validation**
   - System checks if code exists
   - Verifies user isn't using own code
   - Checks if user was already referred

2. **Account Creation**
   - New user account is created
   - `referred_by` field is set
   - User receives their own referral code

3. **Bonus Award**
   - Referrer receives configured bonus (default 500 pieces)
   - Transaction is recorded in `user_pieces_transactions`
   - Referral record is created with status 'credited'
   - Referrer's earnings and count are updated

4. **Confirmation**
   - Success message displayed
   - Both users can see the referral in their dashboards

## API Endpoints

### User Routes (Authenticated)
```
GET  /referrals              - View referral dashboard
```

### Admin Routes (SuperAdmin Only)
```
GET  /admin/referrals                    - Settings dashboard
POST /admin/referrals/update-bonus       - Update bonus amount
POST /admin/referrals/toggle-system      - Enable/disable system
GET  /admin/referrals/all                - View all referrals
GET  /admin/referrals/top-referrers      - Top referrers list
```

## Configuration

### Default Settings
```php
referral_bonus_amount = 500    // Pieces awarded per referral
referral_enabled = true        // System active by default
```

### Modifying Settings
Via Admin Dashboard or tinker:
```php
use App\Models\ReferralSetting;

// Update bonus amount
ReferralSetting::set('referral_bonus_amount', 1000);

// Toggle system
ReferralSetting::set('referral_enabled', 'false');
```

## User Model Methods

```php
// Check if user has referral code
$user->hasReferralCode()

// Generate a new code
$user->generateReferralCode()

// Check if user was referred
$user->wasReferred()

// Get users referred by this user
$user->referredUsers()

// Get referral records
$user->referralsMade()

// Get who referred this user
$user->referredBy()

// Add referral earnings
$user->addReferralEarnings($amount)
```

## Service Methods

```php
use App\Services\ReferralService;

$service = app(ReferralService::class);

// Process a referral
$result = $service->processReferral($newUser, $referralCode);

// Validate code
$isValid = $service->validateReferralCode($code);

// Get user stats
$stats = $service->getUserReferralStats($user);

// Get global stats
$globalStats = $service->getGlobalStats();

// Get/update bonus amount
$amount = $service->getReferralBonusAmount();
$service->updateReferralBonusAmount(1000);

// Check if enabled
$enabled = $service->isEnabled();
$service->toggleSystem(true);
```

## Testing

Run the comprehensive test script:
```bash
php test-referral-system.php
```

This verifies:
- ✅ Settings configuration
- ✅ User codes generation
- ✅ Service functionality
- ✅ Code uniqueness
- ✅ Referral simulation
- ✅ Global statistics
- ✅ Database tables
- ✅ Model methods
- ✅ Routes configuration

## Security Features

- ✅ Users cannot refer themselves
- ✅ Users can only be referred once
- ✅ Referral codes are unique
- ✅ Admin-only bonus configuration
- ✅ Transaction logging for auditing

## Future Enhancements (Optional)

- Multi-level referrals (referrer gets bonus when their referrals refer others)
- Time-limited referral campaigns
- Custom bonus amounts per campaign
- Referral analytics dashboard
- Export referral data
- Email notifications for successful referrals
- QR codes for easy sharing
- Social media sharing integration

## Support

For issues or questions:
1. Check test script output
2. Review logs in `storage/logs`
3. Verify database migrations ran successfully
4. Ensure ReferralService is properly registered

## Changelog

**Version 1.0** (November 22, 2025)
- Initial release
- User referral codes
- Admin configuration dashboard
- Automatic bonus distribution
- Full transaction tracking
- User referral dashboard
- Global statistics
