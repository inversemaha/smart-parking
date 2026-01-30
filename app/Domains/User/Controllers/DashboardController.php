<?php

namespace App\Domains\User\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Booking\Services\BookingService;
use App\Domains\Vehicle\Services\VehicleService;
use App\Domains\Payment\Services\PaymentService;
use App\Shared\Services\CacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        protected BookingService $bookingService,
        protected VehicleService $vehicleService,
        protected PaymentService $paymentService,
        protected CacheService $cacheService
    ) {
        $this->middleware('auth');
    }

    /**
     * Show dashboard - unified for both user and admin.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $isAdmin = $user->hasAnyRole(['admin', 'super-admin']);

        if ($isAdmin) {
            return $this->adminDashboard($request);
        }

        return $this->userDashboard($request);
    }

    /**
     * Legacy dashboard handler - redirects to appropriate dashboard
     */
    public function legacyDashboard(Request $request)
    {
        $user = auth()->user();

        // Check user type and redirect accordingly
        if ($user->hasAnyRole(['admin', 'super-admin', 'manager'])) {
            return redirect()->route('admin.dashboard.index');
        }

        // Regular users (visitors) go to visitor dashboard
        return redirect()->route('visitor.dashboard');
    }

    /**
     * Show user dashboard.
     */
    protected function userDashboard(Request $request)
    {
        $userId = auth()->id();

        // Get user statistics
        $totalVehicles = $this->vehicleService->getUserVehicleCount($userId);
        $activeBookings = $this->bookingService->getActiveBookingCount($userId);
        $totalBookings = $this->bookingService->getUserBookingCount($userId);
        $totalSpent = $this->paymentService->getUserTotalSpent($userId);
        $totalHours = $this->bookingService->getUserTotalParkingHours($userId);

        // Get recent data for display
        $recentBookings = $this->bookingService->getUserRecentBookings($userId, 5);
        $recentVehicles = $this->vehicleService->getUserRecentVehicles($userId, 5);

        return view('user.dashboard', compact(
            'totalVehicles',
            'activeBookings',
            'totalBookings',
            'totalSpent',
            'totalHours',
            'recentBookings',
            'recentVehicles'
        ));
    }

    /**
     * Show admin dashboard.
     */
    protected function adminDashboard(Request $request)
    {
        // Admin dashboard logic moved from Admin\DashboardController
        $dashboardData = $this->getAdminDashboardData();

        return view('admin.dashboard.index', $dashboardData);
    }

    /**
     * Get admin dashboard data.
     */
    protected function getAdminDashboardData(): array
    {
        return [
            'kpis' => $this->getAdminKPIs(),
            'recent_activities' => $this->getRecentActivities(),
            'revenue_stats' => $this->getRevenueStats(),
            'occupancy_stats' => $this->getOccupancyStats(),
        ];
    }

    /**
     * Get admin KPIs.
     */
    protected function getAdminKPIs(): array
    {
        return [
            'total_users' => \App\Domains\User\Models\User::count(),
            'total_vehicles' => \App\Domains\Vehicle\Models\Vehicle::count(),
            'active_bookings' => \App\Domains\Booking\Models\Booking::where('status', 'active')->count(),
            'total_revenue' => \App\Domains\Payment\Models\Payment::where('status', 'completed')->sum('amount'),
        ];
    }

    /**
     * Get recent activities for admin.
     */
    protected function getRecentActivities(): array
    {
        // Implementation for recent activities
        return [];
    }

    /**
     * Get revenue statistics for admin.
     */
    protected function getRevenueStats(): array
    {
        // Implementation for revenue stats
        return [];
    }

    /**
     * Get occupancy statistics for admin.
     */
    protected function getOccupancyStats(): array
    {
        // Implementation for occupancy stats
        return [];
    }

    /**
     * User profile management.
     */
    public function profile(Request $request)
    {
        return view('dashboard.profile', [
            'user' => auth()->user()
        ]);
    }

    /**
     * Update user profile.
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:users,phone,' . $user->id,
            'address' => 'nullable|string|max:500',
            'language_preference' => 'required|in:en,bn',
        ]);

        $user->update($validated);

        return redirect()->route('profile.index')
            ->with('success', __('Profile updated successfully.'));
    }

    /**
     * Update user password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        auth()->user()->update([
            'password' => bcrypt($validated['password'])
        ]);

        return redirect()->route('profile.index')
            ->with('success', __('Password updated successfully.'));
    }

    /**
     * Update user avatar.
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = auth()->user();

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar_path && \Storage::exists($user->avatar_path)) {
                \Storage::delete($user->avatar_path);
            }

            // Store new avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');

            $user->update(['avatar_path' => $avatarPath]);
        }

        return redirect()->route('profile.index')
            ->with('success', __('Avatar updated successfully.'));
    }
}
