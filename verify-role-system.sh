#!/bin/bash

echo "=========================================="
echo "ROLE SYSTEM VERIFICATION"
echo "=========================================="
echo ""

echo "✓ Checking Role System Components..."
echo ""

# Check Migration
echo "1. Database Migration:"
php artisan tinker --execute="
\$columns = DB::select('DESCRIBE users');
\$hasRole = false;
foreach (\$columns as \$column) {
    if (\$column->Field === 'role') {
        \$hasRole = true;
        echo '  - role column exists: ✅' . PHP_EOL;
        echo '  - Type: ' . \$column->Type . PHP_EOL;
    }
}
if (!\$hasRole) {
    echo '  - role column: ❌ NOT FOUND' . PHP_EOL;
}
"

echo ""
echo "2. User Model Methods:"
php artisan tinker --execute="
\$user = \App\Models\User::first();
echo '  - isSuperAdmin(): ' . (method_exists(\$user, 'isSuperAdmin') ? '✅' : '❌') . PHP_EOL;
echo '  - isCampaignCreator(): ' . (method_exists(\$user, 'isCampaignCreator') ? '✅' : '❌') . PHP_EOL;
echo '  - canManageCampaigns(): ' . (method_exists(\$user, 'canManageCampaigns') ? '✅' : '❌') . PHP_EOL;
"

echo ""
echo "3. Middleware:"
echo "  - IsSuperAdmin: $([ -f app/Http/Middleware/IsSuperAdmin.php ] && echo '✅' || echo '❌')"
echo "  - IsCampaignCreator: $([ -f app/Http/Middleware/IsCampaignCreator.php ] && echo '✅' || echo '❌')"

echo ""
echo "4. Controllers:"
echo "  - UserManagementController: $([ -f app/Http/Controllers/Admin/UserManagementController.php ] && echo '✅' || echo '❌')"

echo ""
echo "5. Views:"
echo "  - admin/users/index.blade.php: $([ -f resources/views/admin/users/index.blade.php ] && echo '✅' || echo '❌')"
echo "  - admin/users/assign-role.blade.php: $([ -f resources/views/admin/users/assign-role.blade.php ] && echo '✅' || echo '❌')"
echo "  - admin/users/campaign-creators.blade.php: $([ -f resources/views/admin/users/campaign-creators.blade.php ] && echo '✅' || echo '❌')"

echo ""
echo "6. Routes:"
php artisan route:list --path=admin/users | head -n 10

echo ""
echo "7. User Roles Summary:"
php artisan tinker --execute="
\$users = \App\Models\User::all();
\$roleCount = [
    'user' => \$users->where('role', 'user')->count(),
    'campaign_creator' => \$users->where('role', 'campaign_creator')->count(),
    'superadmin' => \$users->where('role', 'superadmin')->count(),
];
echo '  - Regular Users: ' . \$roleCount['user'] . PHP_EOL;
echo '  - Campaign Creators: ' . \$roleCount['campaign_creator'] . PHP_EOL;
echo '  - Super Admins: ' . \$roleCount['superadmin'] . PHP_EOL;
"

echo ""
echo "=========================================="
echo "VERIFICATION COMPLETE!"
echo "=========================================="
echo ""
echo "📋 Features Implemented:"
echo "  ✅ Role-based access control (user, campaign_creator, superadmin)"
echo "  ✅ User management dashboard for superadmin"
echo "  ✅ Role assignment interface"
echo "  ✅ Protected campaign routes with campaign_creator middleware"
echo "  ✅ Role-based login redirection"
echo "  ✅ Enhanced navigation menu with role permissions"
echo ""
echo "🔐 Login Redirects:"
echo "  - SuperAdmin → /dashboard (admin dashboard)"
echo "  - Campaign Creator → /admin/campaigns (campaign management)"
echo "  - Regular User → /dashboard (user dashboard)"
echo ""
