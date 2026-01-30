<?php

namespace App\Shared\Utils;

class ValidationHelper
{
    /**
     * Bangladesh mobile number validation rules.
     */
    public static function bangladeshMobileRules(): string
    {
        return 'regex:/^(?:\+88|88)?(01[3-9]\d{8})$/';
    }

    /**
     * Bangladesh NID validation rules.
     */
    public static function bangladeshNidRules(): string
    {
        return 'regex:/^(?:\d{10}|\d{13}|\d{17})$/';
    }

    /**
     * Vehicle registration number validation rules.
     */
    public static function vehicleRegistrationRules(): string
    {
        // Bangladesh vehicle registration formats
        return 'regex:/^(?:[A-Z]{2,3}[-\s]?\d{2,4}[-\s]?\d{4}|[A-Z]{2,3}[-\s]?\d{2}[-\s]?\d{4}|HA[-\s]?\d{2}[-\s]?\d{4}|KA[-\s]?\d{2}[-\s]?\d{4})$/i';
    }

    /**
     * Password strength validation rules.
     */
    public static function strongPasswordRules(): array
    {
        return [
            'min:8',
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
        ];
    }

    /**
     * Get common validation messages.
     */
    public static function messages(): array
    {
        return [
            'required' => 'The :attribute field is required.',
            'email' => 'Please provide a valid email address.',
            'unique' => 'This :attribute is already taken.',
            'min' => 'The :attribute must be at least :min characters.',
            'max' => 'The :attribute may not be greater than :max characters.',
            'regex' => 'The :attribute format is invalid.',
            'confirmed' => 'The :attribute confirmation does not match.',
            'date' => 'The :attribute is not a valid date.',
            'date_format' => 'The :attribute does not match the format :format.',
            'after' => 'The :attribute must be a date after :date.',
            'before' => 'The :attribute must be a date before :date.',
            'numeric' => 'The :attribute must be a number.',
            'integer' => 'The :attribute must be an integer.',
            'boolean' => 'The :attribute field must be true or false.',
            'array' => 'The :attribute must be an array.',
            'exists' => 'The selected :attribute is invalid.',
            'in' => 'The selected :attribute is invalid.',
            'mimes' => 'The :attribute must be a file of type: :values.',
            'max_file_size' => 'The :attribute may not be greater than :max kilobytes.',
        ];
    }

    /**
     * Get custom attribute names.
     */
    public static function attributes(): array
    {
        return [
            'name' => 'full name',
            'email' => 'email address',
            'phone' => 'phone number',
            'mobile' => 'mobile number',
            'nid' => 'National ID',
            'license_number' => 'license number',
            'registration_number' => 'registration number',
            'vehicle_type' => 'vehicle type',
            'start_time' => 'start time',
            'end_time' => 'end time',
            'parking_area_id' => 'parking area',
            'parking_slot_id' => 'parking slot',
            'payment_method' => 'payment method',
        ];
    }

    /**
     * Validate Bangladesh mobile number format.
     */
    public static function isValidBangladeshMobile(string $mobile): bool
    {
        $pattern = '/^(?:\+88|88)?(01[3-9]\d{8})$/';
        return preg_match($pattern, $mobile);
    }

    /**
     * Validate Bangladesh NID format.
     */
    public static function isValidBangladeshNid(string $nid): bool
    {
        $pattern = '/^(?:\d{10}|\d{13}|\d{17})$/';
        return preg_match($pattern, $nid);
    }

    /**
     * Validate vehicle registration number format.
     */
    public static function isValidVehicleRegistration(string $registration): bool
    {
        $pattern = '/^(?:[A-Z]{2,3}[-\s]?\d{2,4}[-\s]?\d{4}|[A-Z]{2,3}[-\s]?\d{2}[-\s]?\d{4}|HA[-\s]?\d{2}[-\s]?\d{4}|KA[-\s]?\d{2}[-\s]?\d{4})$/i';
        return preg_match($pattern, $registration);
    }

    /**
     * Sanitize mobile number to standard format.
     */
    public static function sanitizeMobileNumber(string $mobile): string
    {
        // Remove all non-digit characters except +
        $mobile = preg_replace('/[^\d+]/', '', $mobile);

        // Remove country code if present
        if (str_starts_with($mobile, '+88')) {
            $mobile = substr($mobile, 3);
        } elseif (str_starts_with($mobile, '88')) {
            $mobile = substr($mobile, 2);
        }

        return $mobile;
    }

    /**
     * Sanitize vehicle registration number.
     */
    public static function sanitizeVehicleRegistration(string $registration): string
    {
        // Convert to uppercase and normalize spacing
        return strtoupper(preg_replace('/[-\s]+/', '-', trim($registration)));
    }

    /**
     * Check if password meets strength requirements.
     */
    public static function isStrongPassword(string $password): bool
    {
        return strlen($password) >= 8 &&
               preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/', $password);
    }
}
