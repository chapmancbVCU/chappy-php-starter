<h1 style="font-size: 50px; text-align: center;">Queue / Job Dispatching</h1>

## Table of contents
1. [Overview](#overview)
2. [Features](#features)
3. [Real-World Example: Queued Welcome Email](#example)
4. [Creating a Job Class](#job-class)
5. [Configuration](#configuration)
6. [Starting the Worker](#worker)
7. [Job Lifecycle](#lifecycle)
8. [Failed Jobs](#failed-jobs)
9. [Queue Table Migration](#migration)
10. [Bonus: Dispatch Helper (Optional)](#bonus)

<br>

## 1. Overview <a id="overview"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Chappy.php includes a robust queue system that supports asynchronous job processing using either a database or Redis backend.

Use queues to defer time-consuming work like emails, notifications, and data processing — improving user experience and application responsiveness.

<br>

## 2. 🚀 Features <a id="features"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>

- Queueable jobs with delay and retry support
- Database or Redis drivers
- Max attempt limits and backoff strategies
- Job scaffolding via CLI
- Graceful shutdown handling
- Fully integrated CLI worker (`queue:worker`)

<br>

## 3. ✅ Real-World Example: Queued Welcome Email <a id="example"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
You can enqueue a job inside a service layer like this:
```php
use App\Jobs\SendWelcomeEmail;
use Core\Lib\Queue\QueueManager;

class UserService {
    public static function queueWelcomeMailer(int $user_id): void {
        $queue = new QueueManager();
        $job = new SendWelcomeEmail(['user_id' => $user_id], 0); // delay 0s
        $queue->push('default', $job->toPayload());
    }
}
```

This is invoked inside the `registerAction()` method after user creation:
```php
public function registerAction(): void {
    $user = new Users();
    if ($this->request->isPost()) {
        $this->request->csrfCheck();

        ...

        $user->save();

        if ($user->validationPassed()) {
            UserService::queueWelcomeMailer((int)$user->id);
            if ($uploads) {
                ProfileImages::uploadProfileImage($user->id, $uploads);
            }
            redirect('auth.login');
        }
    }

    $this->view->user = $user;
    $this->view->displayErrors = $user->getErrorMessages();
    $this->view->render('auth.register');
}
```

<br>

## 4. 🛠 Creating a Job Class <a id="job-class"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Generate a job using:
```bash
php console make:job SendWelcomeEmail
```

Edit `app/Jobs/SendWelcomeEmail.php` `handle` function:
```php
class SendWelcomeEmail implements QueueableJobInterface {
    protected array $data;
    protected int $delayInSeconds;
    protected int $maxAttempts;

    public function __construct(array $data, int $delayInSeconds = 0, int $maxAttempts = 3) {
        $this->data = $data;
        $this->delayInSeconds = $delayInSeconds;
        $this->maxAttempts = $maxAttempts;
    }

    public function handle(): void {
        $user = Users::findById($this->data['user_id']);
        Tools::info("Sending welcome email to {$user->username} at {$user->email}", "info");
        WelcomeMailer::sendTo($user);
    }

    public function backoff(): int|array {
        return [10, 30, 60]; // Retry intervals in seconds
    }

    public function delay(): int {
        return $this->delayInSeconds;
    }

    public function maxAttempts(): int {
        return $this->maxAttempts;
    }

    public function toPayload(): array {
        return [
            'job' => static::class,
            'data' => $this->data,
            'available_at' => time() + $this->delay(),
            'max_attempts' => $this->maxAttempts()
        ];
    }
}
```

<br>

## 5. ⚙️ Configuration <a id="configuration"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Edit your queue configuration in the `.env` file:
```rust
QUEUE_DRIVER='database'     # or 'redis'
MAX_ATTEMPTS=3
REDIS_HOST='127.0.0.1'
REDIS_PORT='6379'
```

<br>

## 6. 🧃 Starting the Worker <a id="worker"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
Start a worker process to handle jobs:
```bash
php console queue:work
```

Optional arguments:

| Flag             | Description                 |
| ---------------- | --------------------------- |
| `--once`         | Run only one job then exit  |
| `--max=100`      | Run up to 100 iterations    |
| `--queue=emails` | Run only jobs in this queue |

Example:
```bash
php console queue:work --queue=default --max=50
```

<br>

## 7. 🧩 Job Lifecycle <a id="lifecycle"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
* A. Job is enqueued using `push()` or `dispatch()`
* B. Worker pops and reserves the next eligible job
* C. Calls `handle()` on the job instance
* D. If success → deletes the job
* E. If failure → retries with backoff, up to `maxAttempts`

<br>

## 8. 🛑 Failed Jobs <a id="failed-jobs"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
If a job fails:
- It is retried based on `backoff()` and `maxAttempts()`
- After final failure, it’s marked with `failed_at`
- You can inspect job state in the `queue` table

<br>

## 9. 📋 Queue Table Migration <a id="migration"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
To generate the queue table migration:
```bash
php console queue:migration
```

Columns include:
- `queue` – queue name
- `payload` – job data
- `attempts`, `available_at`, `reserved_at`, `failed_at`
- `exception` – captured error message
- Laravel-style timestamps

<br>

## 10. ✨ Bonus: Dispatch Helper (Optional) <a id="bonus"></a><span style="float: right; font-size: 14px; padding-top: 15px;">[Table of Contents](#table-of-contents)</span>
While you can use `push()` like this:
```php
$queue->push('emails', $job->toPayload());
```

You can also dispatch raw class + data:
```php
$queue->dispatch(SendWelcomeEmail::class, ['user_id' => 1], 'emails');
```
