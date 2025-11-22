<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        $settings = Setting::orderBy('key')->get();
        
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update conversion rate
     */
    public function updateConversionRate(Request $request)
    {
        $request->validate([
            'conversion_rate' => 'required|numeric|min:0.0001|max:1000',
        ], [
            'conversion_rate.required' => 'Le taux de conversion est requis.',
            'conversion_rate.numeric' => 'Le taux de conversion doit être un nombre.',
            'conversion_rate.min' => 'Le taux de conversion doit être au moins 0.0001 FCFA.',
            'conversion_rate.max' => 'Le taux de conversion ne peut pas dépasser 1000 FCFA.',
        ]);

        Setting::set(
            'conversion_rate',
            $request->conversion_rate,
            'number',
            'Taux de conversion des pièces en FCFA (1 pièce = X FCFA)'
        );

        return redirect()->route('admin.settings.index')
            ->with('success', 'Taux de conversion mis à jour avec succès.');
    }

    /**
     * Update minimum conversion pieces
     */
    public function updateMinimumPieces(Request $request)
    {
        $request->validate([
            'minimum_conversion_pieces' => 'required|integer|min:100|max:1000000',
        ], [
            'minimum_conversion_pieces.required' => 'Le minimum de pièces est requis.',
            'minimum_conversion_pieces.integer' => 'Le minimum de pièces doit être un nombre entier.',
            'minimum_conversion_pieces.min' => 'Le minimum doit être au moins 100 pièces.',
            'minimum_conversion_pieces.max' => 'Le minimum ne peut pas dépasser 1,000,000 pièces.',
        ]);

        Setting::set(
            'minimum_conversion_pieces',
            $request->minimum_conversion_pieces,
            'number',
            'Nombre minimum de pièces requis pour une conversion'
        );

        return redirect()->route('admin.settings.index')
            ->with('success', 'Minimum de pièces mis à jour avec succès.');
    }

    /**
     * Toggle conversion system
     */
    public function toggleConversion(Request $request)
    {
        $enabled = $request->has('conversion_enabled') ? '1' : '0';

        Setting::set(
            'conversion_enabled',
            $enabled,
            'boolean',
            'Activer/désactiver le système de conversion'
        );

        $message = $enabled === '1' 
            ? 'Système de conversion activé.' 
            : 'Système de conversion désactivé.';

        return redirect()->route('admin.settings.index')
            ->with('success', $message);
    }

    /**
     * Update all settings at once
     */
    public function updateAll(Request $request)
    {
        $request->validate([
            'conversion_rate' => 'required|numeric|min:0.0001|max:1000',
            'minimum_conversion_pieces' => 'required|integer|min:100|max:1000000',
            'conversion_enabled' => 'nullable|boolean',
        ], [
            'conversion_rate.required' => 'Le taux de conversion est requis.',
            'conversion_rate.numeric' => 'Le taux de conversion doit être un nombre.',
            'conversion_rate.min' => 'Le taux de conversion doit être au moins 0.0001 FCFA.',
            'conversion_rate.max' => 'Le taux de conversion ne peut pas dépasser 1000 FCFA.',
            'minimum_conversion_pieces.required' => 'Le minimum de pièces est requis.',
            'minimum_conversion_pieces.integer' => 'Le minimum de pièces doit être un nombre entier.',
            'minimum_conversion_pieces.min' => 'Le minimum doit être au moins 100 pièces.',
            'minimum_conversion_pieces.max' => 'Le minimum ne peut pas dépasser 1,000,000 pièces.',
        ]);

        // Update conversion rate
        Setting::set(
            'conversion_rate',
            $request->conversion_rate,
            'number',
            'Taux de conversion des pièces en FCFA (1 pièce = X FCFA)'
        );

        // Update minimum pieces
        Setting::set(
            'minimum_conversion_pieces',
            $request->minimum_conversion_pieces,
            'number',
            'Nombre minimum de pièces requis pour une conversion'
        );

        // Update enabled status
        $enabled = $request->has('conversion_enabled') ? '1' : '0';
        Setting::set(
            'conversion_enabled',
            $enabled,
            'boolean',
            'Activer/désactiver le système de conversion'
        );

        // Clear all settings cache
        Cache::flush();

        return redirect()->route('admin.settings.index')
            ->with('success', 'Tous les paramètres ont été mis à jour avec succès.');
    }
}
