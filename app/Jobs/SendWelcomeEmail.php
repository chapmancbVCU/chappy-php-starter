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

    public function __construct(array $data, int $delayInSeconds) {
        $this->data = $data;
        $this->delayInSeconds = $delayInSeconds;
    }

    public function handle(): void {
        
        // Actual logic to send email (example stub)
        $user = Users::findById($this->data['user_id']);
        Tools::info("Sending welcome email to {$user->username} at ({$user->email})...", "info");
        // Use your mail system here
        WelcomeMailer::sendTo($user);
    }

    public function toPayload(): array {
        return [
            'job' => static::class,
            'data' => $this->data,
            'available_at' => time() + $this->delayInSeconds
        ];
    }
}
