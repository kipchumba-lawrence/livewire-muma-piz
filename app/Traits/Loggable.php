<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait Loggable
{
    /**
     * Log an info message
     *
     * @param string $message
     * @param array $context
     * @param string|null $channel
     * @return void
     */
    protected function logInfo(string $message, array $context = [], ?string $channel = null): void
    {
        $this->log('info', $message, $context, $channel);
    }

    /**
     * Log a debug message
     *
     * @param string $message
     * @param array $context
     * @param string|null $channel
     * @return void
     */
    protected function logDebug(string $message, array $context = [], ?string $channel = null): void
    {
        $this->log('debug', $message, $context, $channel);
    }

    /**
     * Log a warning message
     *
     * @param string $message
     * @param array $context
     * @param string|null $channel
     * @return void
     */
    protected function logWarning(string $message, array $context = [], ?string $channel = null): void
    {
        $this->log('warning', $message, $context, $channel);
    }

    /**
     * Log an error message
     *
     * @param string $message
     * @param array $context
     * @param string|null $channel
     * @return void
     */
    protected function logError(string $message, array $context = [], ?string $channel = null): void
    {
        $this->log('error', $message, $context, $channel);
    }

    /**
     * Log a critical message
     *
     * @param string $message
     * @param array $context
     * @param string|null $channel
     * @return void
     */
    protected function logCritical(string $message, array $context = [], ?string $channel = null): void
    {
        $this->log('critical', $message, $context, $channel);
    }

    /**
     * Log user activity
     *
     * @param string $action
     * @param array $data
     * @return void
     */
    protected function logUserActivity(string $action, array $data = []): void
    {
        $context = array_merge([
            'user_id' => auth()->id(),
            'user_email' => auth()->user()?->email,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'action' => $action,
        ], $data);

        $this->log('info', "User Activity: {$action}", $context, 'user_activity');
    }

    /**
     * Log M-Pesa transaction
     *
     * @param string $action
     * @param array $data
     * @return void
     */
    protected function logMpesaTransaction(string $action, array $data = []): void
    {
        $context = array_merge([
            'timestamp' => now()->toIso8601String(),
            'action' => $action,
        ], $data);

        $this->log('info', "M-Pesa: {$action}", $context, 'mpesa');
    }

    /**
     * Log pipeline action
     *
     * @param string $action
     * @param int|null $pipelineId
     * @param array $data
     * @return void
     */
    protected function logPipelineAction(string $action, ?int $pipelineId = null, array $data = []): void
    {
        $context = array_merge([
            'pipeline_id' => $pipelineId,
            'user_id' => auth()->id(),
            'timestamp' => now()->toIso8601String(),
            'action' => $action,
        ], $data);

        $this->log('info', "Pipeline {$action}", $context, 'pipeline');
    }

    /**
     * Core logging method
     *
     * @param string $level
     * @param string $message
     * @param array $context
     * @param string|null $channel
     * @return void
     */
    private function log(string $level, string $message, array $context = [], ?string $channel = null): void
    {
        $logger = $channel ? Log::channel($channel) : Log::getFacadeRoot();
        
        // Add class context
        $context['class'] = static::class;
        
        // Add timestamp
        $context['logged_at'] = now()->toIso8601String();

        $logger->$level($message, $context);
    }
}

