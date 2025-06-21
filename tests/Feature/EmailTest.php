<?php
namespace Tests\Feature;
use Core\Lib\Mail\MailerService;
use Core\Lib\Testing\ApplicationTestCase;

/**
 * Unit tests
 */
class EmailTest extends ApplicationTestCase {
    /**
     * Example for testing home page.
     *
     * @return void
     */
    public function test_email_is_sent(): void
    {
        $mail = new MailerService();
        
        // Assert true when using test E-mail service.
        $this->assertTrue($mail->send(
            'user@example.com', 'Welcome!', '<p>Your account is ready!</p>'
        ));
    }

    public function test_email_template(): void {
        $mail = new MailerService();
        $hello = "Hello world";
        $this->assertTrue($mail->sendTemplate(
            'user@example.com',
            'Welcome to ChappyPHP',
            'hello',
            ['user' => $hello],
            'test'
        ));
    }

    public function test_email_text_template(): void {
        $mail = new MailerService();
        $hello = "Hello world";
        $this->assertTrue($mail->sendTemplate(
            'user@example.com',
            'Welcome to ChappyPHP',
            'welcome',
            ['user' => $hello],
        ));
    }

    public function test_email_template_and_single_attachment(): void {
        $mail = new MailerService();
        $hello = "Hello world";
        $this->assertTrue($mail->sendTemplate(
            'user@example.com',
            'Testing attachments',
            'hello',
            ['user' => $hello],
            'test',
            [
                'content' => file_get_contents(CHAPPY_BASE_PATH . DS . 'resources' . DS . 'views' . DS . 'emails' . DS . 'welcome.txt'),
                'name' => 'test attachment',
                'mime' => MailerService::MIME_TEXT
            ]
        ));
    }

    public function test_email_template_and_multiple_multiple(): void {
        $mail = new MailerService();
        $hello = "Hello world";
        $this->assertTrue($mail->sendTemplate(
            'user@example.com',
            'Testing attachments',
            'hello',
            ['user' => $hello],
            'test',
            [
                [
                    'content' => file_get_contents(CHAPPY_BASE_PATH . DS . 'resources' . DS . 'views' . DS . 'emails' . DS . 'welcome.txt'),
                    'name' => 'test content attachment',
                    'mime' => MailerService::MIME_TEXT
                ],
                [
                    'path' => CHAPPY_BASE_PATH . DS . 'resources' . DS . 'views' . DS . 'emails' . DS . 'Description.pdf',
                    'name' => 'test path attachment',
                    'mime' => MailerService::MIME_PDF
                ]
            ]
        ));
    }
}
