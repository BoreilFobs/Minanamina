<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\CampaignController as UserCampaignController;
use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\CampaignApprovalController;
use App\Http\Controllers\Admin\CampaignAnalyticsController;
use App\Http\Controllers\Admin\CampaignValidationController;
use App\Http\Controllers\Admin\PiecesManagementController;
use App\Http\Controllers\Admin\ConversionManagementController;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/', function () {
    return view('welcome');
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
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Campaign Management
    Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
    Route::get('/campaigns/create', [CampaignController::class, 'create'])->name('campaigns.create');
    Route::post('/campaigns', [CampaignController::class, 'store'])->name('campaigns.store');
    Route::get('/campaigns/{campaign}', [CampaignController::class, 'show'])->name('campaigns.show');
    Route::get('/campaigns/{campaign}/edit', [CampaignController::class, 'edit'])->name('campaigns.edit');
    Route::put('/campaigns/{campaign}', [CampaignController::class, 'update'])->name('campaigns.update');
    Route::delete('/campaigns/{campaign}', [CampaignController::class, 'destroy'])->name('campaigns.destroy');
    Route::post('/campaigns/{campaign}/submit-approval', [CampaignController::class, 'submitForApproval'])->name('campaigns.submit-approval');
    Route::post('/campaigns/{campaign}/duplicate', [CampaignController::class, 'duplicate'])->name('campaigns.duplicate');
    
    // Campaign Approvals
    Route::get('/campaigns/approvals/pending', [CampaignApprovalController::class, 'index'])->name('campaigns.approvals.index');
    Route::post('/campaigns/{campaign}/approve', [CampaignApprovalController::class, 'approve'])->name('campaigns.approvals.approve');
    Route::post('/campaigns/{campaign}/reject', [CampaignApprovalController::class, 'reject'])->name('campaigns.approvals.reject');
    Route::post('/campaigns/{campaign}/request-modifications', [CampaignApprovalController::class, 'requestModifications'])->name('campaigns.approvals.request-modifications');
    Route::post('/campaigns/{campaign}/pause', [CampaignApprovalController::class, 'pause'])->name('campaigns.pause');
    Route::post('/campaigns/{campaign}/resume', [CampaignApprovalController::class, 'resume'])->name('campaigns.resume');
    
    // Campaign Analytics
    Route::get('/campaigns/{campaign}/analytics', [CampaignAnalyticsController::class, 'index'])->name('campaigns.analytics.show');
    Route::get('/campaigns/{campaign}/analytics/export', [CampaignAnalyticsController::class, 'export'])->name('campaigns.analytics.export');
    
    // Campaign Validation (Complete Participations)
    Route::get('/validations', [CampaignValidationController::class, 'index'])->name('validations.index');
    Route::post('/validations/{participation}/validate', [CampaignValidationController::class, 'validate'])->name('validations.validate');
    Route::post('/validations/{participation}/reject', [CampaignValidationController::class, 'reject'])->name('validations.reject');
    Route::post('/validations/bulk-validate', [CampaignValidationController::class, 'bulkValidate'])->name('validations.bulk-validate');
    
    // Pieces Management
    Route::get('/pieces', [PiecesManagementController::class, 'index'])->name('pieces.index');
    Route::get('/pieces/users/{user}', [PiecesManagementController::class, 'userTransactions'])->name('pieces.user-transactions');
    Route::get('/pieces/users/{user}/adjust', [PiecesManagementController::class, 'adjustmentForm'])->name('pieces.adjustment.form');
    Route::post('/pieces/users/{user}/adjust', [PiecesManagementController::class, 'processAdjustment'])->name('pieces.adjustment.process');
    Route::get('/pieces/transactions/{transaction}/reverse', [PiecesManagementController::class, 'reversalForm'])->name('pieces.reversal.form');
    Route::post('/pieces/transactions/{transaction}/reverse', [PiecesManagementController::class, 'processReversal'])->name('pieces.reversal.process');
    Route::post('/pieces/users/{user}/toggle-suspicious', [PiecesManagementController::class, 'toggleSuspicious'])->name('pieces.toggle-suspicious');
    Route::get('/pieces/export', [PiecesManagementController::class, 'export'])->name('pieces.export');
    
    // Conversion Management
    Route::get('/conversions', [ConversionManagementController::class, 'index'])->name('conversions.index');
    Route::get('/conversions/{conversion}', [ConversionManagementController::class, 'show'])->name('conversions.show');
    Route::post('/conversions/{conversion}/approve', [ConversionManagementController::class, 'approve'])->name('conversions.approve');
    Route::post('/conversions/{conversion}/reject', [ConversionManagementController::class, 'reject'])->name('conversions.reject');
    Route::post('/conversions/{conversion}/processing', [ConversionManagementController::class, 'markProcessing'])->name('conversions.processing');
    Route::post('/conversions/{conversion}/completed', [ConversionManagementController::class, 'markCompleted'])->name('conversions.completed');
    Route::post('/conversions/{conversion}/notes', [ConversionManagementController::class, 'addNotes'])->name('conversions.notes');
    Route::get('/conversions/export', [ConversionManagementController::class, 'export'])->name('conversions.export');
});
