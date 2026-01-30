<?php

namespace App\Jobs;

use App\Domains\User\Models\User;
use App\Shared\Services\AuditLogService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Job to clean up expired user sessions and tokens
 */
class CleanupExpiredUserSessions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 1;

    /**
     * Execute the job.
     */
    public function handle(AuditLogService $auditLogService): void
    {
        try {
            $cleanedSessions = 0;
            $cleanedTokens = 0;

            // Clean up expired user sessions (older than 30 days)
            $expiredSessions = \App\Domains\User\Models\UserSession::where('last_activity_at', '<', now()->subDays(30))->get();

            foreach ($expiredSessions as $session) {
                $session->delete();
                $cleanedSessions++;
            }

            // Clean up expired API tokens (Laravel Sanctum)
            $users = User::with(['tokens'])->get();

            foreach ($users as $user) {
                foreach ($user->tokens as $token) {
                    // If token hasn't been used for 30 days, delete it
                    if ($token->last_used_at && $token->last_used_at->lt(now()->subDays(30))) {
                        $token->delete();
                        $cleanedTokens++;
                    }
                }
            }

            // Log the cleanup activity
            $auditLogService->log(
                'session_cleanup',
                'Cleaned up expired user sessions and tokens',
                null,
                null,
                [
                    'cleaned_sessions' => $cleanedSessions,
                    'cleaned_tokens' => $cleanedTokens
                ]
            );

            Log::info('User session cleanup completed', [
                'cleaned_sessions' => $cleanedSessions,
                'cleaned_tokens' => $cleanedTokens
            ]);

        } catch (\Exception $e) {
            Log::error('User session cleanup failed', [
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }
}
