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

        $senderEmail    = '[Your email]';
        $senderUsername = '[Your username]';
        $senderPassword = '[Your password]';

        // act
        $mailSender = new MailSender(
            $phpMailerStub, 
            $senderEmail,
            $senderUsername,
            $senderPassword
        );

        $mailSender->setHost('smtp.gmail.com')
                   ->setPort(587)
                   ->setCharset('UTF-8');

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
        
        $senderEmail    = '[Your email]';
        $senderUsername = '[Your username]';
        $senderPassword = '[Your password]';

        // act
        $mailSender = new MailSender(
            $phpMailerStub, 
            $senderEmail,
            $senderUsername,
            $senderPassword
        );

        $mailSender->setHost('smtp.gmail.com')
                   ->setPort(587)
                   ->setCharset('UTF-8');

        // assert
        $this->assertFalse($mailSender->sendEmail(
            'subject',
            'body',
            'felixchirindza30@gmail'
        ));
    }
}
