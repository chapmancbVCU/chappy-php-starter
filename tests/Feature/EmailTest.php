<?php
namespace Tests\Feature;

use App\Models\EmailAttachments;
use App\Models\Users;
use Core\Lib\Mail\Attachments;
use Core\Lib\Mail\MailerService;
use Core\Lib\Mail\WelcomeMailer;
use Core\Lib\Mail\PasswordResetMailer;
use Core\Lib\Mail\UpdatePasswordMailer;
use Core\Lib\Testing\ApplicationTestCase;
use Core\Lib\Mail\AccountDeactivatedMailer;

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
        $user = Users::findById(1);

        // Assert true when using test E-mail service.
        $this->assertTrue($mail->send(
            $user->email, 'test_email_is_sent', '<p>Your account is ready!</p>'
        ));
    }

    public function test_email_template(): void {
        $mail = new MailerService();
        $user = Users::findById(1);
        $this->assertTrue($mail->sendTemplate(
            $user->email,
            'test_email_template',
            'hello',
            ['user' => $user->username],
            'default',
            [],
            null,
            null,
            'email_default'
        ));
    }

    public function test_email_text_template(): void {
        $mail = new MailerService();
        $user = Users::findById(1);
        $this->assertTrue($mail->sendTemplate(
            $user->email,
            'test_email_text_template',
            'welcome',
            ['user' => $user->username],
            'default',
            [],
            null,
            null,
            'email_default'
        ));
    }

    public function test_email_single_attachment_and_template(): void {
        $attachment = EmailAttachments::findById(2);
        $mail = new MailerService();
        $user = Users::findById(1);
        $this->assertTrue($mail->sendTemplate(
            $user->email,
            'test_email_single_attachment_and_template',
            'hello',
            ['user' => $user->username],
            'default',
            Attachments::content($attachment),
            null,
            null,
            'email_default'
        ));
    }

    public function test_email_multiple_attachments_and_template(): void {
        $attachment1 = EmailAttachments::findById(1);
        $attachment2 = EmailAttachments::findById(2);
        $mail = new MailerService();
        $user = Users::findById(1);
        $this->assertTrue($mail->sendTemplate(
            $user->email,
            'test_email_multiple_attachments_and_template',
            'hello',
            ['user' => $user->username],
            'default',
            [
                Attachments::content($attachment2),
                Attachments::path($attachment1)
            ],
            null,
            null,
            'email_default'
        ));
    }

    public function test_email_text_template_with_attachments(): void {
        $attachment1 = EmailAttachments::findById(1);
        $attachment2 = EmailAttachments::findById(2);
        $mail = new MailerService();
        $user = Users::findById(1);
        $this->assertTrue($mail->sendTemplate(
            $user->email,
            'test_email_text_template_with_attachments',
            'welcome',
            ['user' => $user->username],
            'default',
            [
                Attachments::content($attachment2),
                Attachments::path($attachment1)
            ],
            null,
            null,
            'email_default'
        ));
    }

    // public function test_welcome_email(): void {
    //     $status = WelcomeMailer::send(Users::findById(1));
    //     $this->assertTrue($status);
    // }

    // public function test_password_reset_email(): void {
    //     $status = PasswordResetMailer::send(Users::findById(1));
    //     $this->assertTrue($status);
    // }

    // public function test_password_update_email(): void {
    //     $status = UpdatePasswordMailer::send(Users::findById(1));
    //     $this->assertTrue($status);
    // }

    // public function test_account_deactivated_email(): void {
    //     $status = AccountDeactivatedMailer::send(Users::findById(1));
    //     $this->assertTrue($status);
    // }
}
