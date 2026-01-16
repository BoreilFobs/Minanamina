<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\CampaignController as UserCampaignController;
use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\CampaignApprovalController;
use App\Http\Controllers\Admin\CampaignAnalyticsController;
use App\Http\Controllers\Admin\CampaignValidationController;
use App\Http\Controllers\Admin\PiecesManagementController;
use App\Http\Controllers\Admin\ConversionManagementController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\ReferralSettingsController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Creator\DashboardController as CreatorDashboardController;
use App\Http\Controllers\Creator\CampaignController as CreatorCampaignController;
use App\Http\Controllers\Creator\AnalyticsController as CreatorAnalyticsController;
use App\Http\Controllers\Creator\ParticipationController as CreatorParticipationController;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    // Registration
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // Password Reset
    Route::get('/forgot-password', [PasswordResetController::class, 'showResetRequestForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

// Verification Routes (accessible during registration)
Route::get('/verify', [VerificationController::class, 'show'])->name('verification.show');
Route::post('/verify', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('/verify/resend', [VerificationController::class, 'resend'])->name('verification.resend');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/notifications', [ProfileController::class, 'updateNotificationPreferences'])->name('profile.notifications');
    Route::post('/profile/privacy', [ProfileController::class, 'updatePrivacySettings'])->name('profile.privacy');
});

// Public Campaign Routes
Route::get('/campaigns', [UserCampaignController::class, 'index'])->name('campaigns.index');
Route::get('/campaigns/{campaign}', [UserCampaignController::class, 'show'])->name('campaigns.show');
Route::post('/campaigns/{campaign}/participate', [UserCampaignController::class, 'participate'])->name('campaigns.participate')->middleware('auth');
Route::get('/my-participations', [UserCampaignController::class, 'myParticipations'])->name('campaigns.my-participations')->middleware('auth');

// Reward System Routes (Authenticated Users)
Route::middleware('auth')->group(function () {
    // Pieces & Transactions
    Route::get('/rewards', [RewardController::class, 'index'])->name('rewards.index');
    Route::get('/rewards/transactions/export', [RewardController::class, 'exportTransactions'])->name('rewards.transactions.export');
    
    // Conversions
    Route::get('/rewards/convert', [RewardController::class, 'conversionForm'])->name('rewards.convert.form');
    Route::post('/rewards/convert', [RewardController::class, 'submitConversion'])->name('rewards.convert.submit');
    Route::get('/rewards/conversions', [RewardController::class, 'conversions'])->name('rewards.conversions');
    Route::get('/rewards/conversions/{conversion}', [RewardController::class, 'showConversion'])->name('rewards.conversions.show');
    
    // Referral System
    Route::get('/referrals', [ReferralController::class, 'index'])->name('referrals.index');
});

// SuperAdmin Only Routes
Route::middleware(['auth', 'superadmin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // User Management
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/assign-role', [UserManagementController::class, 'assignRoleForm'])->name('users.assign-role.form');
    Route::post('/users/{user}/assign-role', [UserManagementController::class, 'assignRole'])->name('users.assign-role');
    Route::delete('/users/{user}/remove-role', [UserManagementController::class, 'removeRole'])->name('users.remove-role');
    Route::get('/campaign-creators', [UserManagementController::class, 'campaignCreators'])->name('users.campaign-creators');
    
    // Referral Management
    Route::get('/referrals', [ReferralSettingsController::class, 'index'])->name('referrals.index');
    Route::post('/referrals/update-bonus', [ReferralSettingsController::class, 'updateBonus'])->name('referrals.update-bonus');
    Route::post('/referrals/update-new-user-bonus', [ReferralSettingsController::class, 'updateNewUserBonus'])->name('referrals.update-new-user-bonus');
    Route::post('/referrals/toggle-system', [ReferralSettingsController::class, 'toggleSystem'])->name('referrals.toggle-system');
    Route::get('/referrals/all', [ReferralSettingsController::class, 'allReferrals'])->name('referrals.all');
    Route::get('/referrals/top-referrers', [ReferralSettingsController::class, 'topReferrers'])->name('referrals.top-referrers');
    
    // Campaign Approvals (SuperAdmin only)
    Route::get('/campaigns/approvals/pending', [CampaignApprovalController::class, 'index'])->name('campaigns.approvals.index');
    Route::post('/campaigns/{campaign}/approve', [CampaignApprovalController::class, 'approve'])->name('campaigns.approvals.approve');
    Route::post('/campaigns/{campaign}/reject', [CampaignApprovalController::class, 'reject'])->name('campaigns.approvals.reject');
    Route::post('/campaigns/{campaign}/request-modifications', [CampaignApprovalController::class, 'requestModifications'])->name('campaigns.approvals.request-modifications');
    Route::post('/campaigns/{campaign}/pause', [CampaignApprovalController::class, 'pause'])->name('campaigns.pause');
    Route::post('/campaigns/{campaign}/resume', [CampaignApprovalController::class, 'resume'])->name('campaigns.resume');
    
    // Campaign Validation (SuperAdmin only)
    Route::get('/validations', [CampaignValidationController::class, 'index'])->name('validations.index');
    Route::post('/validations/{participation}/validate', [CampaignValidationController::class, 'validate'])->name('validations.validate');
    Route::post('/validations/{participation}/reject', [CampaignValidationController::class, 'reject'])->name('validations.reject');
    Route::post('/validations/bulk-validate', [CampaignValidationController::class, 'bulkValidate'])->name('validations.bulk-validate');
    
    // Pieces Management (SuperAdmin only)
    Route::get('/pieces', [PiecesManagementController::class, 'index'])->name('pieces.index');
    Route::get('/pieces/users/{user}', [PiecesManagementController::class, 'userTransactions'])->name('pieces.user-transactions');
    Route::get('/pieces/users/{user}/adjust', [PiecesManagementController::class, 'adjustmentForm'])->name('pieces.adjustment.form');
    Route::post('/pieces/users/{user}/adjust', [PiecesManagementController::class, 'processAdjustment'])->name('pieces.adjustment.process');
    Route::get('/pieces/transactions/{transaction}/reverse', [PiecesManagementController::class, 'reversalForm'])->name('pieces.reversal.form');
    Route::post('/pieces/transactions/{transaction}/reverse', [PiecesManagementController::class, 'processReversal'])->name('pieces.reversal.process');
    Route::post('/pieces/users/{user}/toggle-suspicious', [PiecesManagementController::class, 'toggleSuspicious'])->name('pieces.toggle-suspicious');
    Route::get('/pieces/export', [PiecesManagementController::class, 'export'])->name('pieces.export');
    
    // Conversion Management (SuperAdmin only)
    Route::get('/conversions', [ConversionManagementController::class, 'index'])->name('conversions.index');
    Route::get('/conversions/{conversion}', [ConversionManagementController::class, 'show'])->name('conversions.show');
    Route::post('/conversions/{conversion}/approve', [ConversionManagementController::class, 'approve'])->name('conversions.approve');
    Route::post('/conversions/{conversion}/reject', [ConversionManagementController::class, 'reject'])->name('conversions.reject');
    Route::post('/conversions/{conversion}/processing', [ConversionManagementController::class, 'markProcessing'])->name('conversions.processing');
    Route::post('/conversions/{conversion}/completed', [ConversionManagementController::class, 'markCompleted'])->name('conversions.completed');
    Route::post('/conversions/{conversion}/notes', [ConversionManagementController::class, 'addNotes'])->name('conversions.notes');
    Route::get('/conversions/export', [ConversionManagementController::class, 'export'])->name('conversions.export');
    
    // Settings Management (SuperAdmin only)
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/update-all', [SettingsController::class, 'updateAll'])->name('settings.update-all');
    Route::post('/settings/conversion-rate', [SettingsController::class, 'updateConversionRate'])->name('settings.conversion-rate');
    Route::post('/settings/minimum-pieces', [SettingsController::class, 'updateMinimumPieces'])->name('settings.minimum-pieces');
    Route::post('/settings/toggle-conversion', [SettingsController::class, 'toggleConversion'])->name('settings.toggle-conversion');
    
    // Admin Campaign Management (for superadmins managing all campaigns)
    Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
    Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('campaigns.create');
    Route::post('/campaigns', [CampaignController::class, 'store'])->name('campaigns.store');
    Route::get('/campaigns/{campaign}', [CampaignController::class, 'show'])->name('campaigns.show');
    Route::get('/campaigns/{campaign}/edit', [CampaignController::class, 'edit'])->name('campaigns.edit');
    Route::put('/campaigns/{campaign}', [CampaignController::class, 'update'])->name('campaigns.update');
    Route::delete('/campaigns/{campaign}', [CampaignController::class, 'destroy'])->name('campaigns.destroy');
    Route::post('/campaigns/{campaign}/submit-approval', [CampaignController::class, 'submitForApproval'])->name('campaigns.submit-approval');
    Route::post('/campaigns/{campaign}/duplicate', [CampaignController::class, 'duplicate'])->name('campaigns.duplicate');
    
    // Campaign Analytics (SuperAdmin)
    Route::get('/campaigns/{campaign}/analytics', [CampaignAnalyticsController::class, 'index'])->name('campaigns.analytics.show');
    Route::get('/campaigns/{campaign}/analytics/export', [CampaignAnalyticsController::class, 'export'])->name('campaigns.analytics.export');
});

// ============================================
// Campaign Creator Routes (Separate Dashboard)
// ============================================
Route::middleware(['auth', 'campaign_creator'])->prefix('creator')->name('creator.')->group(function () {
    // Creator Dashboard
    Route::get('/', [CreatorDashboardController::class, 'index'])->name('dashboard');
    
    // Campaign Management
    Route::get('/campaigns', [CreatorCampaignController::class, 'index'])->name('campaigns.index');
    Route::get('/campaigns/create', [CreatorCampaignController::class, 'create'])->name('campaigns.create');
    Route::post('/campaigns', [CreatorCampaignController::class, 'store'])->name('campaigns.store');
    Route::get('/campaigns/{campaign}', [CreatorCampaignController::class, 'show'])->name('campaigns.show');
    Route::get('/campaigns/{campaign}/edit', [CreatorCampaignController::class, 'edit'])->name('campaigns.edit');
    Route::put('/campaigns/{campaign}', [CreatorCampaignController::class, 'update'])->name('campaigns.update');
    Route::delete('/campaigns/{campaign}', [CreatorCampaignController::class, 'destroy'])->name('campaigns.destroy');
    Route::post('/campaigns/{campaign}/submit-approval', [CreatorCampaignController::class, 'submitForApproval'])->name('campaigns.submit-approval');
    Route::post('/campaigns/{campaign}/duplicate', [CreatorCampaignController::class, 'duplicate'])->name('campaigns.duplicate');
    
    // Analytics
    Route::get('/analytics', [CreatorAnalyticsController::class, 'index'])->name('analytics');
    
    // Participations Management
    Route::get('/participations', [CreatorParticipationController::class, 'index'])->name('participations');
    Route::post('/participations/{participation}/validate', [CreatorParticipationController::class, 'validate'])->name('participations.validate');
    Route::post('/participations/{participation}/reject', [CreatorParticipationController::class, 'reject'])->name('participations.reject');
});
