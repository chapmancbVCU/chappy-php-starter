<?php
declare(strict_types=1);

namespace App\Jobs;

use Core\Lib\Queue\QueueableJobInterface;

class SendWelcomeEmail implements QueueableJobInterface {
    protected array $data;

    public function __construct(array $data) {
        $this->data = $data;
    }

    public function handle(): void {
        // Actual logic to send email (example stub)
        $to = $this->data['email'];
        $name = $this->data['name'];
        echo "Sending welcome email to {$name} ({$to})...\n";
        
        // Use your mail system here
    }

    public function toPayload(): array {
        return [
            'job' => static::class,
            'data' => $this->data
        ];
    }
}
