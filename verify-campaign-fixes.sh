#!/bin/bash

# Campaign CRUD Verification Script
# Run this after the fixes to verify everything works

echo "=========================================="
echo "Campaign CRUD - Verification Script"
echo "=========================================="
echo ""

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo -e "${RED}Error: artisan file not found. Please run from project root.${NC}"
    exit 1
fi

echo "1. Checking migrations..."
php artisan migrate:status | grep "make_affiliate_link_nullable"
if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Migration found and applied${NC}"
else
    echo -e "${RED}✗ Migration not found${NC}"
fi
echo ""

echo "2. Testing Campaign Model..."
php artisan tinker --execute="
try {
    \$campaign = new App\Models\Campaign([
        'title' => 'Verification Test',
        'description' => 'Testing after fixes',
        'cpa_link' => 'https://example.com/verify',
        'pieces_reward' => 100,
        'start_date' => now(),
        'end_date' => now()->addDays(7),
        'created_by' => 1,
        'status' => 'draft'
    ]);
    \$campaign->save();
    echo '✓ Campaign created successfully (ID: ' . \$campaign->id . ')';
    \$campaign->delete();
    echo ' - Deleted';
} catch (\Exception \$e) {
    echo '✗ Error: ' . \$e->getMessage();
}
"
echo ""

echo "3. Checking table structure..."
php artisan tinker --execute="
\$table = DB::select(\"SHOW COLUMNS FROM campaigns WHERE Field = 'affiliate_link'\");
if (count(\$table) > 0) {
    \$nullable = \$table[0]->Null;
    if (\$nullable === 'YES') {
        echo '✓ affiliate_link is nullable';
    } else {
        echo '✗ affiliate_link is NOT nullable';
    }
}
"
echo ""

echo "4. Checking cpa_link column..."
php artisan tinker --execute="
\$table = DB::select(\"SHOW COLUMNS FROM campaigns WHERE Field = 'cpa_link'\");
if (count(\$table) > 0) {
    echo '✓ cpa_link column exists';
} else {
    echo '✗ cpa_link column NOT found';
}
"
echo ""

echo "5. Testing date casting..."
php artisan tinker --execute="
\$campaign = App\Models\Campaign::latest()->first();
if (\$campaign) {
    echo 'Latest campaign: ' . \$campaign->title;
    echo ' | Start: ' . \$campaign->start_date->format('Y-m-d');
    echo ' | End: ' . \$campaign->end_date->format('Y-m-d');
    echo ' | CPA: ' . (\$campaign->cpa_link ?? 'N/A');
} else {
    echo 'No campaigns found in database';
}
"
echo ""

echo "=========================================="
echo "Verification Complete!"
echo "=========================================="
echo ""
echo "Next Steps:"
echo "1. Visit: http://127.0.0.1:8888/admin/campaigns/create"
echo "2. Create a test campaign"
echo "3. Edit the campaign to verify dates display correctly"
echo "4. Check the documentation:"
echo "   - FIXES/CAMPAIGN_CRUD_ALL_FIXES.md"
echo "   - FIXES/CAMPAIGN_AFFILIATE_LINK_FIX.md"
echo ""
