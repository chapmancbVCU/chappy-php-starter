<?php

return [
    'channels' => [
        'database' => Core\Lib\Notifications\Channels\DatabaseChannel::class,
        'mail' => Core\Lib\Notifications\Channels\MailChannel::class
    ]
];