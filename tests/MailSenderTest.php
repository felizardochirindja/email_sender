<?php

namespace MailSenderTests;

require_once '../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use MailSender\MailSender;
use PHPMailer\PHPMailer\PHPMailer;

class MailSenderTest extends TestCase
{
    public function testEmailSent()
    {
        // arrange
        $phpMailerStub = $this->createStub(PHPMailer::class);
        $phpMailerStub->method('send')->willReturn(true);
        // $phpMailer = new PHPMailer();

        // act
        $mailSender = new MailSender($phpMailerStub);

        // assert
        $this->assertTrue($mailSender->sendEmail(
            'subject',
            'body',
            'felixchirindza30@gmail.com'
        ));
    }

    public function testEmailNotSentWithInvalidEmail()
    {
        // arrange
        $phpMailerStub = $this->createStub(PHPMailer::class);
        $phpMailerStub->method('send')->willReturn(false);
        // $phpMailer = new PHPMailer();

        // act
        $mailSender = new MailSender($phpMailerStub);

        // assert
        $this->assertFalse($mailSender->sendEmail(
            'subject',
            'body',
            'felixchirindza30@gmail'
        ));
    }
}
