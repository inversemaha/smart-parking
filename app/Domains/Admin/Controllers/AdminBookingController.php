<?php

namespace App\Domains\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Booking\Models\Booking;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manage_bookings|admin');
    }

    /**
     * Display a listing of all bookings.
     */
    public function index(Request $request)
    {
        $query = Booking::with('user', 'vehicle', 'parkingLocation', 'payment');

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_number', 'ilike', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'ilike', "%{$search}%");
                  })
                  ->orWhereHas('vehicle', function ($q) use ($search) {
                      $q->where('brand', 'ilike', "%{$search}%")
                        ->orWhere('model', 'ilike', "%{$search}%");
                  });
            });
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Display the specified booking.
     */
    public function show(Booking $booking)
    {
        $booking->load('user', 'vehicle', 'parkingLocation', 'payment');
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Confirm a pending booking.
     */
    public function confirm(Booking $booking)
    {
        try {
            if ($booking->status !== 'pending') {
                return back()->with('error', 'Only pending bookings can be confirmed');
            }

            $booking->update([
                'status' => 'confirmed',
                'confirmed_at' => now(),
            ]);

            return redirect()
                ->route('admin.bookings.show', $booking->id)
                ->with('success', 'Booking confirmed successfully');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Failed to confirm booking: ' . $e->getMessage());
        }
    }

    /**
     * Cancel a booking.
     */
    public function cancel(Request $request, Booking $booking)
    {
        try {
            if (!in_array($booking->status, ['pending', 'confirmed'])) {
                return back()->with('error', 'Only pending or confirmed bookings can be cancelled');
            }

            $booking->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancelled_by' => auth()->id(),
                'cancellation_reason' => $request->input('reason', 'Cancelled by admin'),
            ]);

            // TODO: Process refund if payment was made

            return redirect()
                ->route('admin.bookings.show', $booking->id)
                ->with('success', 'Booking cancelled successfully');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Failed to cancel booking: ' . $e->getMessage());
        }
    }
}
