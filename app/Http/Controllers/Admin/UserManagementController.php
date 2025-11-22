<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserManagementController extends Controller
{
    /**
     * Display list of all users
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Search by name or phone
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show form to assign campaign creator role
     */
    public function assignRoleForm(User $user)
    {
        return view('admin.users.assign-role', compact('user'));
    }

    /**
     * Assign campaign creator role to user
     */
    public function assignRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:user,campaign_creator,superadmin',
        ]);

        // Prevent removing own superadmin role
        if ($user->id === auth()->id() && $request->role !== 'superadmin') {
            return back()->with('error', 'Vous ne pouvez pas modifier votre propre rôle de super admin!');
        }

        $user->update([
            'role' => $request->role,
            'is_admin' => in_array($request->role, ['superadmin', 'campaign_creator']),
        ]);

        $roleNames = [
            'user' => 'Utilisateur',
            'campaign_creator' => 'Créateur de Campagnes',
            'superadmin' => 'Super Administrateur',
        ];

        return redirect()->route('admin.users.index')
            ->with('success', "Rôle mis à jour: {$user->name} est maintenant {$roleNames[$request->role]}");
    }

    /**
     * Remove campaign creator role
     */
    public function removeRole(User $user)
    {
        // Prevent removing own superadmin role
        if ($user->id === auth()->id() && $user->isSuperAdmin()) {
            return back()->with('error', 'Vous ne pouvez pas vous retirer le rôle de super admin!');
        }

        $user->update([
            'role' => 'user',
            'is_admin' => false,
        ]);

        return back()->with('success', "{$user->name} est maintenant un utilisateur simple.");
    }

    /**
     * Get campaign creators list
     */
    public function campaignCreators()
    {
        $creators = User::where('role', 'campaign_creator')
            ->orWhere('role', 'superadmin')
            ->withCount('campaigns')
            ->latest()
            ->paginate(15);

        return view('admin.users.campaign-creators', compact('creators'));
    }
}
