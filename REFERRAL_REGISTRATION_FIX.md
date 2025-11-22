# Referral Registration Fix

## Problem
`SQLSTATE[HY000]: General error: 1364 Field 'referral_code' doesn't have a default value`

This error occurred during user registration because the `referral_code` column was defined as `NOT NULL` without a default value, but the code generates the referral code AFTER user creation.

## Root Cause
In `database/migrations/0001_01_01_000000_create_users_table.php`:
```php
$table->string('referral_code')->unique(); // ❌ NOT NULL, no default
```

## Solution Applied

### 1. Updated Migration
Changed the `referral_code` column to be nullable:
```php
$table->string('referral_code')->unique()->nullable(); // ✅ Now nullable
```

### 2. Updated RegisteredUserController
Added referral code generation and processing to `app/Http/Controllers/Auth/RegisteredUserController.php`:

```php
// Inject ReferralService
protected $referralService;

public function __construct(ReferralService $referralService)
{
    $this->referralService = $referralService;
}

// In store() method:
$user = User::create([
    'name' => $request->name,
    'phone' => $request->phone,
    'password' => Hash::make($request->password),
    'phone_verified_at' => now(),
    'status' => 'active',
    'pieces_balance' => 0,
    'role' => 'user',
]);

// Generate referral code for new user
$user->generateReferralCode();

// Handle referral if provided
if ($request->referral_code) {
    $result = $this->referralService->processReferral($user, $request->referral_code);
    
    if ($result['success']) {
        session()->flash('referral_success', $result['message']);
    }
}
```

### 3. Updated Validation
Changed from email to phone-based registration:
```php
$request->validate([
    'name' => ['required', 'string', 'max:255'],
    'phone' => ['required', 'string', 'max:20', 'unique:'.User::class, 'regex:/^\+?[0-9]{9,15}$/'],
    'password' => ['required', 'confirmed', Rules\Password::defaults()],
    'referral_code' => ['nullable', 'string'],
]);
```

## Registration Flow

1. **User submits registration form** (with optional referral code)
2. **Validation** checks all fields including referral code format
3. **User created** in database (referral_code is NULL initially)
4. **Referral code generated** via `$user->generateReferralCode()`
5. **Referral processed** (if code provided):
   - Validates referral code exists
   - Awards 500 pieces to referrer
   - Awards 100 pieces to new user
   - Creates transaction records
   - Updates referral statistics
6. **User logged in** automatically
7. **Redirected to dashboard** with success message

## Test Results

✅ User creation without referral code: **PASSED**
- User created successfully
- Referral code auto-generated (e.g., "TES8664")
- Balance: 0 pieces

✅ User creation with referral code: **PASSED**
- User created successfully
- Referral code auto-generated
- Referrer receives: 500 pieces
- New user receives: 100 pieces
- Both transactions logged correctly

## Files Modified

1. `/database/migrations/0001_01_01_000000_create_users_table.php`
   - Made `referral_code` nullable

2. `/app/Http/Controllers/Auth/RegisteredUserController.php`
   - Added ReferralService dependency injection
   - Added referral code generation after user creation
   - Added referral processing logic
   - Changed from email to phone validation

## Database Schema

```sql
CREATE TABLE users (
    ...
    referral_code VARCHAR(255) NULL UNIQUE,
    referred_by BIGINT NULL,
    total_referrals INT DEFAULT 0,
    referral_earnings DECIMAL(15,2) DEFAULT 0,
    ...
);
```

## Important Notes

1. **Order Matters**: User must be created BEFORE generating referral code (needs user ID)
2. **Nullable Column**: `referral_code` must be nullable to allow creation first
3. **Unique Constraint**: Still enforced, preventing duplicate codes
4. **Automatic Generation**: All users get a unique referral code upon registration
5. **Bonus Distribution**: Happens atomically within a database transaction

## Migration Command

To apply the fix to existing databases:
```bash
php artisan migrate:fresh --seed
```

⚠️ **Warning**: This will drop all tables and recreate them. Use in development only!

For production, create a specific migration to alter the column:
```php
Schema::table('users', function (Blueprint $table) {
    $table->string('referral_code')->unique()->nullable()->change();
});
```
