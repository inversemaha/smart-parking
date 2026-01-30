<?php

namespace App\Domains\Payment\Repositories;

use App\Domains\Payment\Models\Payment;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PaymentRepository
{
    protected $model;

    public function __construct(Payment $model)
    {
        $this->model = $model;
    }

    /**
     * Create a new payment.
     */
    public function create(array $data): Payment
    {
        return $this->model->create($data);
    }

    /**
     * Update payment.
     */
    public function update($model, array $data)
    {
        $model->update($data);
        return $model->fresh();
    }

    /**
     * Find payment by ID.
     */
    public function find(int $id): ?Payment
    {
        return $this->model->with(['booking', 'user'])->find($id);
    }

    /**
     * Find payment by transaction ID.
     */
    public function findByTransactionId(string $transactionId): ?Payment
    {
        return $this->model->where('transaction_id', $transactionId)->first();
    }

    /**
     * Get user payments.
     */
    public function getUserPayments(int $userId, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->where('user_id', $userId)
                            ->with(['booking.vehicle', 'booking.parkingSlot.parkingLocation']);

        // Apply filters
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['gateway'])) {
            $query->where('gateway', $filters['gateway']);
        }

        if (isset($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->orderBy('created_at', 'desc')
                    ->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Get all payments with filters.
     */
    public function getAllPayments(array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->with([
            'user',
            'booking.vehicle',
            'booking.parkingSlot.parkingLocation'
        ]);

        // Apply filters
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['gateway'])) {
            $query->where('gateway', $filters['gateway']);
        }

        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['amount_from'])) {
            $query->where('amount', '>=', $filters['amount_from']);
        }

        if (isset($filters['amount_to'])) {
            $query->where('amount', '<=', $filters['amount_to']);
        }

        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('transaction_id', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('gateway_transaction_id', 'like', '%' . $filters['search'] . '%')
                  ->orWhereHas('user', function ($userQuery) use ($filters) {
                      $userQuery->where('name', 'like', '%' . $filters['search'] . '%')
                               ->orWhere('email', 'like', '%' . $filters['search'] . '%');
                  });
            });
        }

        if (isset($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->orderBy('created_at', 'desc')
                    ->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Get payment statistics.
     */
    public function getStatistics(array $filters = []): array
    {
        $query = $this->model->query();

        // Apply date filters
        if (isset($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        $baseQuery = clone $query;

        return [
            'total_payments' => $baseQuery->count(),
            'completed' => (clone $query)->where('status', 'completed')->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'failed' => (clone $query)->where('status', 'failed')->count(),
            'cancelled' => (clone $query)->where('status', 'cancelled')->count(),
            'total_amount' => (clone $query)->where('status', 'completed')->sum('amount'),
            'pending_amount' => (clone $query)->where('status', 'pending')->sum('amount'),
            'failed_amount' => (clone $query)->where('status', 'failed')->sum('amount'),
            'average_amount' => (clone $query)->where('status', 'completed')->avg('amount'),
            'success_rate' => $this->calculateSuccessRate($query),
            'by_gateway' => $this->getPaymentsByGateway($query),
        ];
    }

    /**
     * Get failed payments for retry.
     */
    public function getFailedPayments(): Collection
    {
        return $this->model->where('status', 'failed')
                          ->where('retry_count', '<', 3)
                          ->with(['booking', 'user'])
                          ->orderBy('created_at', 'asc')
                          ->get();
    }

    /**
     * Get pending refunds.
     */
    public function getPendingRefunds(): Collection
    {
        return $this->model->where('refund_status', 'pending')
                          ->with(['booking', 'user'])
                          ->orderBy('refund_requested_at', 'asc')
                          ->get();
    }

    /**
     * Calculate success rate.
     */
    private function calculateSuccessRate($query): float
    {
        $total = (clone $query)->count();

        if ($total === 0) {
            return 0.0;
        }

        $successful = (clone $query)->where('status', 'completed')->count();

        return round(($successful / $total) * 100, 2);
    }

    /**
     * Get payments by gateway.
     */
    private function getPaymentsByGateway($query): array
    {
        return (clone $query)->selectRaw('gateway, COUNT(*) as count, SUM(amount) as total')
                            ->groupBy('gateway')
                            ->get()
                            ->keyBy('gateway')
                            ->map(function ($item) {
                                return [
                                    'count' => $item->count,
                                    'total' => $item->total,
                                ];
                            })
                            ->toArray();
    }
}
