<?php

namespace App\Policies;

use App\Domains\User\Models\User;
use App\Domains\Payment\Models\Payment;
use Illuminate\Auth\Access\HandlesAuthorization;

class VisitorPaymentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given payment can be viewed by the user.
     */
    public function view(User $user, Payment $payment): bool
    {
        return $user->id === $payment->user_id;
    }

    /**
     * Determine if the user can create payments.
     */
    public function create(User $user): bool
    {
        return true; // Any authenticated visitor can make payments
    }

    /**
     * Determine if the payment can be refunded by the user.
     */
    public function refund(User $user, Payment $payment): bool
    {
        // Can only request refund for own payments
        if ($user->id !== $payment->user_id) {
            return false;
        }

        // Can only refund paid payments
        if ($payment->status !== 'paid') {
            return false;
        }

        // Check refund deadline (e.g., within 24 hours of booking cancellation)
        if ($payment->booking && $payment->booking->status === 'cancelled') {
            $refundDeadline = $payment->booking->cancelled_at?->addHours(24);
            return $refundDeadline && now() < $refundDeadline;
        }

        return false;
    }

    /**
     * Determine if the user can download payment receipt.
     */
    public function download(User $user, Payment $payment): bool
    {
        return $user->id === $payment->user_id &&
               in_array($payment->status, ['paid', 'refunded']);
    }
}
