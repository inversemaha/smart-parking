<?php

namespace App\Shared\Utils;

class ResponseHelper
{
    /**
     * Create a success API response.
     */
    public static function success($data = null, string $message = 'Success', int $statusCode = 200): array
    {
        return [
            'success' => true,
            'message' => $message,
            'data' => $data,
            'timestamp' => now()->toISOString(),
        ];
    }

    /**
     * Create an error API response.
     */
    public static function error(string $message = 'Error', $errors = null, int $statusCode = 400): array
    {
        return [
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'timestamp' => now()->toISOString(),
        ];
    }

    /**
     * Create a validation error response.
     */
    public static function validationError($errors, string $message = 'Validation failed'): array
    {
        return self::error($message, $errors, 422);
    }

    /**
     * Create a not found response.
     */
    public static function notFound(string $message = 'Resource not found'): array
    {
        return self::error($message, null, 404);
    }

    /**
     * Create an unauthorized response.
     */
    public static function unauthorized(string $message = 'Unauthorized'): array
    {
        return self::error($message, null, 401);
    }

    /**
     * Create a forbidden response.
     */
    public static function forbidden(string $message = 'Forbidden'): array
    {
        return self::error($message, null, 403);
    }

    /**
     * Create a paginated response.
     */
    public static function paginated($data, string $message = 'Success'): array
    {
        return [
            'success' => true,
            'message' => $message,
            'data' => $data->items(),
            'pagination' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem(),
                'has_more_pages' => $data->hasMorePages(),
            ],
            'timestamp' => now()->toISOString(),
        ];
    }

    /**
     * Create a created response.
     */
    public static function created($data = null, string $message = 'Resource created successfully'): array
    {
        return self::success($data, $message, 201);
    }

    /**
     * Create an updated response.
     */
    public static function updated($data = null, string $message = 'Resource updated successfully'): array
    {
        return self::success($data, $message, 200);
    }

    /**
     * Create a deleted response.
     */
    public static function deleted(string $message = 'Resource deleted successfully'): array
    {
        return self::success(null, $message, 200);
    }

    /**
     * Format exception response.
     */
    public static function exception(\Throwable $exception, bool $debug = false): array
    {
        $response = [
            'success' => false,
            'message' => $exception->getMessage() ?: 'Internal Server Error',
            'timestamp' => now()->toISOString(),
        ];

        if ($debug) {
            $response['debug'] = [
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
            ];
        }

        return $response;
    }
}
