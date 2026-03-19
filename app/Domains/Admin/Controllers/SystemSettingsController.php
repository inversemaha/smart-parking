<?php

namespace App\Domains\Admin\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class SystemSettingsController extends Controller
{
    /**
     * Display system settings form.
     */
    public function index()
    {
        // Get all settings from database or cache
        $settings = Cache::remember('system_settings', 3600, function () {
            return SystemSetting::pluck('value', 'key')->toArray();
        });

        return view('admin.system.settings', compact('settings'));
    }

    /**
     * Update system settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'system_name' => 'required|string|max:255',
            'system_description' => 'nullable|string|max:1000',
            'support_email' => 'required|email',
            'support_phone' => 'nullable|string',
            'support_website' => 'nullable|url',
            'hourly_rate' => 'required|numeric|min:0',
            'daily_rate' => 'required|numeric|min:0',
            'monthly_rate' => 'required|numeric|min:0',
            'operating_hours_from' => 'required|date_format:H:i',
            'operating_hours_to' => 'required|date_format:H:i',
            'enable_24_hours' => 'nullable|boolean',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'currency' => 'required|in:BDT,USD,INR',
            'payment_gateway' => 'required|in:sslcommerz,stripe,manual',
            'smtp_host' => 'nullable|string',
            'smtp_port' => 'nullable|numeric',
            'smtp_username' => 'nullable|string',
            'from_email' => 'nullable|email',
            'maintenance_mode' => 'nullable|boolean',
            'maintenance_message' => 'nullable|string|max:500',
        ]);

        // Save settings
        foreach ($validated as $key => $value) {
            SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        // Clear cache
        Cache::forget('system_settings');

        return redirect()->route('admin.settings.index')
                       ->with('success', 'System settings updated successfully!');
    }
}
