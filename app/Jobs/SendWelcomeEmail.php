<?php
declare(strict_types=1);

namespace App\Jobs;

use App\Models\Users;
use Console\Helpers\Tools;
use Core\Lib\Mail\WelcomeMailer;
use Core\Lib\Queue\QueueableJobInterface;

class SendWelcomeEmail implements QueueableJobInterface {
    protected array $data;
    protected int $delayInSeconds;
    protected int $maxAttempts;

    public function __construct(array $data, int $delayInSeconds = 0, int $maxAttempts = 3) {
        $this->data = $data;
        $this->delayInSeconds = $delayInSeconds;
        $this->maxAttempts = $maxAttempts;
    }

    public function backoff(): int|array {
        return [10, 30, 60];
    }

    public function delay(): int {
        return $this->delayInSeconds;
    }

    public function handle(): void {
        
        // Actual logic to send email (example stub)
        $user = Users::findById($this->data['user_id']);
        Tools::info("Sending welcome email to {$user->username} at ({$user->email})...", "info");
        // Use your mail system here
        WelcomeMailer::sendTo($user);
    }

    public function maxAttempts(): int {
        return $this->maxAttempts;
    }

    public function toPayload(): array {
        return [
            'job' => static::class,
            'data' => $this->data,
            'available_at' => time() + $this->delay(),
            'max_attempts' => $this->maxAttempts(),
        ];
    }
}
