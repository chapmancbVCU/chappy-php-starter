<?php

return [
    'channels' => [
        'database' => Core\Lib\Notifications\Channels\DatabaseChannel::class,
        'log' => Core\Lib\Notifications\Channels\LogChannel::class,
        'mail' => Core\Lib\Notifications\Channels\MailChannel::class
    ]
];