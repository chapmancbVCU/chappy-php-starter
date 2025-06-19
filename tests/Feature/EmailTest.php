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
        $this->assertNotTrue($mail->send(
            'user@example.com', 'Welcome!', '<p>Your account is ready!</p>'
        ));
    }
}
