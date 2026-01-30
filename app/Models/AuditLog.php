<?php

namespace App\Models;

/**
 * AuditLog Model Facade
 *
 * This is a facade that points to the actual AuditLog model in the Admin domain.
 * Keeping this for Laravel compatibility and IDE support.
 */
class AuditLog extends \App\Domains\Admin\Models\AuditLog
{
    // This class extends the domain AuditLog model
    // No additional code needed - it inherits everything
}
