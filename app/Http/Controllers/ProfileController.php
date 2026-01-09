<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'nullable|string|max:2',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'country']);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $avatarPath;
        }

        $user->update($data);

        return redirect()->route('profile.show')
            ->with('success', 'Profil mis à jour avec succès!');
    }

    public function updateNotificationPreferences(Request $request)
    {
        $user = Auth::user();

        $preferences = [
            'sms_notifications' => $request->boolean('sms_notifications'),
            'campaign_updates' => $request->boolean('campaign_updates'),
            'referral_updates' => $request->boolean('referral_updates'),
            'payment_updates' => $request->boolean('payment_updates'),
        ];

        $user->update(['notification_preferences' => $preferences]);

        return back()->with('success', 'Préférences de notification mises à jour avec succès!');
    }

    public function updatePrivacySettings(Request $request)
    {
        $user = Auth::user();

        $settings = [
            'show_profile' => $request->boolean('show_profile'),
            'show_earnings' => $request->boolean('show_earnings'),
            'show_referrals' => $request->boolean('show_referrals'),
        ];

        $user->update(['privacy_settings' => $settings]);

        return back()->with('success', 'Paramètres de confidentialité mis à jour avec succès!');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = Auth::user();

        // Delete user's avatar if exists
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Logout user
        Auth::logout();

        // Delete user account
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Votre compte a été supprimé avec succès.');
    }
}

