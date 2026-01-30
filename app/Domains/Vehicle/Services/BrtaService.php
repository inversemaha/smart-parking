<?php

namespace App\Domains\Vehicle\Services;

use App\Domains\Vehicle\Models\Vehicle;
use App\Domains\Vehicle\Models\BrtaConfig;
use App\Domains\Vehicle\Models\BrtaVerificationLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class BrtaService
{
    protected $config;

    public function __construct()
    {
        $this->config = BrtaConfig::first();
    }

    /**
     * Verify vehicle with BRTA API.
     */
    public function verifyVehicle(Vehicle $vehicle): bool
    {
        if (!$this->config || !$this->config->is_active) {
            Log::info('BRTA verification disabled');
            return false;
        }

        DB::beginTransaction();

        try {
            // Log verification attempt
            $log = BrtaVerificationLog::create([
                'vehicle_id' => $vehicle->id,
                'registration_number' => $vehicle->registration_number,
                'status' => 'initiated',
                'request_data' => [
                    'registration_number' => $vehicle->registration_number,
                    'chassis_number' => $vehicle->chassis_number,
                ],
            ]);

            // Call BRTA API
            $response = $this->callBrtaApi($vehicle);

            if ($response['success']) {
                // Verification successful
                $log->update([
                    'status' => 'verified',
                    'response_data' => $response['data'],
                    'verified_at' => now(),
                ]);

                $vehicle->update([
                    'verification_status' => 'verified',
                    'verified_at' => now(),
                    'brta_data' => $response['data'],
                ]);

                $this->incrementSuccessCount();

            } else {
                // Verification failed
                $log->update([
                    'status' => 'failed',
                    'response_data' => $response['data'] ?? null,
                    'error_message' => $response['message'],
                ]);

                $vehicle->update([
                    'verification_status' => 'failed',
                    'verification_notes' => $response['message'],
                ]);

                $this->incrementFailureCount();
            }

            DB::commit();

            return $response['success'];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('BRTA verification error: ' . $e->getMessage());

            // Log error
            if (isset($log)) {
                $log->update([
                    'status' => 'error',
                    'error_message' => $e->getMessage(),
                ]);
            }

            return false;
        }
    }

    /**
     * Call BRTA API.
     */
    private function callBrtaApi(Vehicle $vehicle): array
    {
        try {
            $response = Http::timeout($this->config->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->config->api_key,
                    'Content-Type' => 'application/json',
                ])
                ->post($this->config->api_url . '/verify', [
                    'registration_number' => $vehicle->registration_number,
                    'chassis_number' => $vehicle->chassis_number,
                ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['status']) && $data['status'] === 'verified') {
                    return [
                        'success' => true,
                        'data' => $data,
                        'message' => 'Vehicle verified successfully',
                    ];
                }
            }

            return [
                'success' => false,
                'data' => $response->json(),
                'message' => 'Vehicle verification failed',
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'API call failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get verification statistics.
     */
    public function getVerificationStats(): array
    {
        return [
            'total_requests' => BrtaVerificationLog::count(),
            'verified' => BrtaVerificationLog::where('status', 'verified')->count(),
            'failed' => BrtaVerificationLog::where('status', 'failed')->count(),
            'errors' => BrtaVerificationLog::where('status', 'error')->count(),
            'success_rate' => $this->calculateSuccessRate(),
        ];
    }

    /**
     * Update BRTA configuration.
     */
    public function updateConfig(array $data): bool
    {
        try {
            if ($this->config) {
                $this->config->update($data);
            } else {
                $this->config = BrtaConfig::create($data);
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to update BRTA config: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Test API connection.
     */
    public function testConnection(): array
    {
        if (!$this->config) {
            return [
                'success' => false,
                'message' => 'BRTA configuration not found',
            ];
        }

        try {
            $response = Http::timeout($this->config->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->config->api_key,
                ])
                ->get($this->config->api_url . '/health');

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Connection successful',
                    'response_time' => $response->transferStats->getTransferTime(),
                ];
            }

            return [
                'success' => false,
                'message' => 'API returned error: ' . $response->status(),
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Connection failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Calculate success rate.
     */
    private function calculateSuccessRate(): float
    {
        $total = BrtaVerificationLog::count();

        if ($total === 0) {
            return 0.0;
        }

        $successful = BrtaVerificationLog::where('status', 'verified')->count();

        return round(($successful / $total) * 100, 2);
    }

    /**
     * Increment success count.
     */
    private function incrementSuccessCount(): void
    {
        if ($this->config) {
            $this->config->increment('success_count');
        }
    }

    /**
     * Increment failure count.
     */
    private function incrementFailureCount(): void
    {
        if ($this->config) {
            $this->config->increment('failure_count');
        }
    }
}
