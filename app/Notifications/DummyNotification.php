<?php
namespace App\Notifications;

use App\Models\Users;
use Core\Lib\Notifications\Channel;
use Core\Lib\Notifications\Notification;

/**
 * DummyNotification notification.
 */
class DummyNotification extends Notification {


    /**
    * Logs notification to log file.
    *
    * @param object $notifiable Any model/object that uses the Notifiable trait.
    * @return string Contents for the log.
    */
    public function toLog(object $notifiable): string {
        return "Test message";
    }


    /**
    * Specify which channels to deliver to.
    * 
    * @param object $notifiable Any model/object that uses the Notifiable trait.
    * @return list<'database'|'mail'|'log'
    */
    public function via(object $notifiable): array {
        return [Channel::LOG->value];
    }

    public function toArray(object $notifiable): array
    {
        return ['message' => 'Test array payload'];
    }
}