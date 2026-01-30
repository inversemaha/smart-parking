<?php

namespace App\Domains\Vehicle\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class BrtaConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'api_url',
        'api_key',
        'api_secret',
        'headers',
        'auth_config',
        'timeout',
        'retry_attempts',
        'is_active',
        'is_sandbox',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'headers' => 'array',
            'auth_config' => 'array',
            'is_active' => 'boolean',
            'is_sandbox' => 'boolean',
        ];
    }

    /**
     * BRTA verification logs relationship.
     */
    public function verificationLogs()
    {
        return $this->hasMany(BrtaVerificationLog::class);
    }

    /**
     * Get decrypted API key.
     */
    public function getDecryptedApiKeyAttribute()
    {
        return $this->api_key ? Crypt::decryptString($this->api_key) : null;
    }

    /**
     * Get decrypted API secret.
     */
    public function getDecryptedApiSecretAttribute()
    {
        return $this->api_secret ? Crypt::decryptString($this->api_secret) : null;
    }

    /**
     * Set encrypted API key.
     */
    public function setApiKeyAttribute($value)
    {
        $this->attributes['api_key'] = $value ? Crypt::encryptString($value) : null;
    }

    /**
     * Set encrypted API secret.
     */
    public function setApiSecretAttribute($value)
    {
        $this->attributes['api_secret'] = $value ? Crypt::encryptString($value) : null;
    }

    /**
     * Scope active configurations.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope sandbox configurations.
     */
    public function scopeSandbox($query)
    {
        return $query->where('is_sandbox', true);
    }

    /**
     * Scope production configurations.
     */
    public function scopeProduction($query)
    {
        return $query->where('is_sandbox', false);
    }
}
