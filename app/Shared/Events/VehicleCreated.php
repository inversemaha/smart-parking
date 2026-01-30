<?php

namespace App\Shared\Events;

use App\Domains\Vehicle\Models\Vehicle;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VehicleCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Vehicle $vehicle;

    /**
     * Create a new event instance.
     */
    public function __construct(Vehicle $vehicle)
    {
        $this->vehicle = $vehicle;
    }
}
