<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Switch application language.
     */
    public function switch(Request $request)
    {
        $locale = $request->get('locale');

        // Validate locale
        if (!in_array($locale, ['en', 'bn'])) {
            return response()->json(['error' => 'Invalid locale'], 400);
        }

        // Set session
        Session::put('locale', $locale);

        // Update user preference if authenticated
        if (Auth::check()) {
            Auth::user()->update(['locale' => $locale]);
        }

        // Return response based on request type
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'locale' => $locale,
                'message' => __('general.language') . ' ' . __('general.updated_at')
            ]);
        }

        return redirect()->back()->with('success', __('general.language') . ' updated successfully.');
    }
}
