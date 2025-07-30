<?php
declare(strict_types=1);

namespace App\Jobs;

use App\Models\Users;
use Core\Lib\Mail\WelcomeMailer;
use Core\Lib\Queue\QueueableJobInterface;

class SendWelcomeEmail implements QueueableJobInterface {
    protected array $data;

    public function __construct(array $data) {
        $this->data = $data;
    }

    public function handle(): void {
        
        // Actual logic to send email (example stub)
        $user = Users::findById($this->data['user_id']);
        echo "Sending welcome email to {$user->username} at ({$user->email})...\n";
        // Use your mail system here
        WelcomeMailer::sendTo($user);
    }

    public function toPayload(): array {
        return [
            'job' => static::class,
            'data' => $this->data
        ];
    }
}
