<?php

namespace App\Shared\Events;

use App\Domains\Vehicle\Models\Vehicle;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VehicleVerified
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Vehicle $vehicle;
    public string $verificationMethod;

    /**
     * Create a new event instance.
     */
    public function __construct(Vehicle $vehicle, string $verificationMethod = 'brta')
    {
        $this->vehicle = $vehicle;
        $this->verificationMethod = $verificationMethod;
    }
}
