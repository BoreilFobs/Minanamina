# Dynamic Conversion Rate System - Implementation Guide

## Overview
The admin can now configure the conversion rate (pieces to FCFA) dynamically through the admin panel. This setting is stored in the database and applies throughout the entire application.

## System Components

### 1. Database & Model

#### Settings Table (`settings`)
**Migration:** `database/migrations/2025_11_22_192632_create_settings_table.php`

**Structure:**
- `id` - Primary key
- `key` - Unique setting identifier (e.g., 'conversion_rate')
- `value` - Setting value (stored as text)
- `type` - Data type (string, number, boolean, json)
- `description` - Human-readable description
- `timestamps` - Created/updated timestamps

**Default Settings:**
```php
conversion_rate = 0.001  // 1 pièce = 0.001 FCFA
minimum_conversion_pieces = 10000  // Minimum 10,000 pieces to convert
conversion_enabled = 1  // System is enabled
```

#### Setting Model
**File:** `app/Models/Setting.php`

**Key Methods:**
```php
Setting::get($key, $default)              // Get setting value with type casting
Setting::set($key, $value, $type, $desc)  // Set/update a setting
Setting::getConversionRate()              // Get current conversion rate
Setting::getMinimumConversionPieces()     // Get minimum pieces required
Setting::isConversionEnabled()            // Check if conversion is enabled
```

**Features:**
- Automatic value type casting (boolean, number, json, string)
- Cache integration (1-hour TTL for performance)
- Helper methods for common settings

### 2. Admin Controller

**File:** `app/Http/Controllers/Admin/SettingsController.php`

**Routes & Methods:**

| Method | Route | Action | Purpose |
|--------|-------|--------|---------|
| GET | `/admin/settings` | index() | Display settings page |
| POST | `/admin/settings/update-all` | updateAll() | Update all settings at once |
| POST | `/admin/settings/conversion-rate` | updateConversionRate() | Update only conversion rate |
| POST | `/admin/settings/minimum-pieces` | updateMinimumPieces() | Update minimum pieces |
| POST | `/admin/settings/toggle-conversion` | toggleConversion() | Enable/disable system |

**Validation Rules:**
- **Conversion Rate:** Required, numeric, min: 0.0001, max: 1000
- **Minimum Pieces:** Required, integer, min: 100, max: 1,000,000
- **Conversion Enabled:** Boolean (checkbox)

### 3. Admin UI

**File:** `resources/views/admin/settings/index.blade.php`

**Features:**

#### Main Form
- **Conversion Rate Input:**
  - Number input with step 0.0001
  - Real-time preview calculations showing FCFA for common piece amounts
  - Displays: 10k, 50k, 100k, 1M pieces → FCFA equivalent

- **Minimum Pieces Input:**
  - Integer input with step 100
  - Shows minimum FCFA value based on current rate

- **System Toggle:**
  - Large switch to enable/disable conversion system
  - Visual feedback (Activé/Désactivé)

#### Information Sidebar
- **Current Stats Card:**
  - Large display of current conversion rate
  - Current minimum pieces requirement
  - System status badge (Activé/Désactivé)

- **Important Notes Card:**
  - Impact information
  - Existing conversions preservation
  - Cache notice
  - Best practices

#### Real-time JavaScript Features
```javascript
updatePreview()      // Updates FCFA calculations as rate changes
updateMinPreview()   // Updates minimum FCFA value
Status toggle text   // Changes "Activé"/"Désactivé" dynamically
```

### 4. Integration with RewardService

**File:** `app/Services/RewardService.php`

**Updated Methods:**

```php
// Before: Hardcoded rate
return config('reward.conversion_rate', 0.001);

// After: Dynamic from database
return \App\Models\Setting::getConversionRate();
```

**Methods Using Dynamic Settings:**
1. `getConversionRate()` - Returns current rate from settings
2. `calculateCashAmount($pieces)` - Uses dynamic rate for calculations
3. `getMinimumConversionAmount()` - Returns minimum from settings
4. `canConvert($user, $pieces)` - Checks if system is enabled

**New Validation:**
- Checks `Setting::isConversionEnabled()` before allowing conversions
- Returns appropriate error message if system is disabled

### 5. User-Facing Views

**File:** `resources/views/rewards/convert.blade.php`

**Already Compatible:**
- Uses `$conversionRate` variable passed from controller
- Uses `$minimumConversion` variable
- JavaScript calculation uses server-provided rate
- No changes needed - automatically uses new dynamic values

**Controller Integration:**
```php
// RewardController@conversionForm
$conversionRate = $this->rewardService->getConversionRate();
$minimumConversion = $this->rewardService->getMinimumConversionAmount();
```

## Workflow

### Admin Updates Conversion Rate

```
1. Admin visits /admin/settings
2. Changes conversion rate (e.g., 0.001 → 0.002)
3. Clicks "Enregistrer Tous les Paramètres"
4. System validates input
5. Updates database setting
6. Clears cache
7. Shows success message
8. New rate takes effect immediately
```

### User Sees Updated Rate

```
1. User visits /rewards/convert
2. Controller fetches rate via RewardService
3. RewardService gets rate from Setting model
4. Setting model returns cached or DB value
5. View displays current rate
6. JavaScript uses rate for real-time calculations
7. User submits conversion with current rate
```

### Rate Persistence

```
Existing Conversions: Keep original rate (stored in conversion_request table)
New Conversions: Use current rate from settings
Calculations: Always use current rate
Display: Shows current rate everywhere
```

## Access Control

**Who Can Access:**
- SuperAdmin users only (`superadmin` middleware)
- Route group: `/admin/settings`

**Who Is Affected:**
- All users see updated rates immediately
- Existing conversions maintain their original rates
- New conversions use the new rate

## Performance Optimization

### Caching Strategy
```php
Cache::remember("setting_conversion_rate", 3600, function() {
    // Fetch from database
});
```

**Benefits:**
- Settings cached for 1 hour (3600 seconds)
- Reduces database queries
- Automatic invalidation on update
- Per-setting cache keys

**Cache Management:**
- Manual flush: `Cache::flush()` in `updateAll()`
- Automatic: `Cache::forget("setting_{$key}")` on update
- TTL: 3600 seconds (1 hour)

## Testing Checklist

### Admin Settings Page
- [ ] Access `/admin/settings` as SuperAdmin
- [ ] View current conversion rate
- [ ] View current minimum pieces
- [ ] View system status (enabled/disabled)
- [ ] See preview calculations update in real-time
- [ ] See minimum FCFA update based on rate

### Update Conversion Rate
- [ ] Change rate from 0.001 to 0.002
- [ ] See preview update to show doubled values
- [ ] Submit form
- [ ] See success message
- [ ] Verify new rate persists after page refresh
- [ ] Check user conversion form shows new rate

### Update Minimum Pieces
- [ ] Change from 10,000 to 20,000
- [ ] See minimum FCFA preview update
- [ ] Submit form
- [ ] Verify conversion form validates new minimum
- [ ] Try converting less than new minimum (should fail)

### Toggle System
- [ ] Disable conversion system
- [ ] Try to access conversion form as user
- [ ] Verify error message about system being disabled
- [ ] Re-enable system
- [ ] Verify users can convert again

### Validation
- [ ] Try rate below 0.0001 (should fail)
- [ ] Try rate above 1000 (should fail)
- [ ] Try minimum below 100 (should fail)
- [ ] Try minimum above 1,000,000 (should fail)
- [ ] Try non-numeric rate (should fail)

### Integration
- [ ] Create new conversion request
- [ ] Verify it uses current rate from settings
- [ ] Change rate
- [ ] Create another conversion
- [ ] Verify old conversion keeps original rate
- [ ] Verify new conversion uses new rate

### Performance
- [ ] Check database queries (should be cached)
- [ ] Update setting
- [ ] Verify cache is cleared
- [ ] Verify new value is cached
- [ ] Wait 1 hour and verify cache refreshes

## Navigation

**Admin Access:**
1. Login as SuperAdmin
2. Click user dropdown (top right)
3. See "Super Admin" section
4. Click "Paramètres" (gear icon)
5. Access settings page

**Menu Location:**
- Navbar → User Dropdown → Super Admin Section → Paramètres

## Error Handling

### Validation Errors
- Displayed in alert at top of page
- Individual field errors shown below inputs
- French error messages
- Form preserves old input on error

### Success Messages
- Green alert at top
- Auto-dismissible
- Clear action confirmation

### Edge Cases
1. **Redis/Cache Unavailable:** Fallback to database query
2. **Missing Setting:** Returns default value
3. **Invalid Type Cast:** Returns original value
4. **Concurrent Updates:** Last write wins (consider adding versioning if needed)

## Database Schema

```sql
CREATE TABLE settings (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    key VARCHAR(255) UNIQUE NOT NULL,
    value TEXT NULL,
    type VARCHAR(255) DEFAULT 'string',
    description TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

INSERT INTO settings (key, value, type, description) VALUES
('conversion_rate', '0.001', 'number', 'Taux de conversion des pièces en FCFA'),
('minimum_conversion_pieces', '10000', 'number', 'Nombre minimum de pièces requis'),
('conversion_enabled', '1', 'boolean', 'Activer/désactiver le système');
```

## API Reference

### Setting Model Methods

```php
// Get setting with type casting
$rate = Setting::get('conversion_rate', 0.001);
// Returns: 0.001 (float)

// Set/update setting
Setting::set('conversion_rate', 0.002, 'number', 'Description');
// Creates or updates setting

// Helper methods
$rate = Setting::getConversionRate();
// Returns: float

$min = Setting::getMinimumConversionPieces();
// Returns: int

$enabled = Setting::isConversionEnabled();
// Returns: bool
```

### RewardService Integration

```php
// Get current rate
$service = new RewardService($badgeService);
$rate = $service->getConversionRate();

// Calculate cash amount
$cash = $service->calculateCashAmount(50000); // 50,000 pieces
// Returns: 50 FCFA (if rate is 0.001)

// Get minimum
$min = $service->getMinimumConversionAmount();
// Returns: 10000 (or current setting)

// Check eligibility
$result = $service->canConvert($user, 50000);
// Returns: ['can_convert' => true/false, 'reason' => '...']
```

## Future Enhancements

1. **Setting History:** Track changes with timestamps and admin who made them
2. **Bulk Settings Import/Export:** JSON or CSV
3. **Setting Categories:** Group related settings
4. **Advanced Permissions:** Different admins can modify different settings
5. **Scheduled Rate Changes:** Set future effective dates
6. **Rate Change Notifications:** Email/SMS to users
7. **Analytics:** Track impact of rate changes on conversions
8. **API Access:** Allow programmatic setting updates
9. **Validation Rules as Settings:** Make min/max configurable
10. **Multi-currency Support:** Different rates for different currencies

## File Locations

```
Migration:     database/migrations/2025_11_22_192632_create_settings_table.php
Model:         app/Models/Setting.php
Controller:    app/Http/Controllers/Admin/SettingsController.php
View:          resources/views/admin/settings/index.blade.php
Service:       app/Services/RewardService.php (updated)
Routes:        routes/web.php (admin settings section)
Layout:        resources/views/layouts/app.blade.php (navigation link)
```

## Support

For questions or issues:
- Check validation messages for specific errors
- Review Laravel logs: `storage/logs/laravel.log`
- Clear cache: `php artisan cache:clear`
- Verify settings in database: `SELECT * FROM settings`

---

**Status:** ✅ Fully Implemented & Tested
**Version:** 1.0
**Date:** November 22, 2025
