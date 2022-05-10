<?php

namespace MailSender;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailException;

class MailSender
{
    private string $emailError;
    
    // Server settings
    const SERVER_HOST    = 'smtp.gmail.com';
    const SERVER_PORT    = 587;
    const SERVER_USER    = 'felizardo.chirindja@gmail.com';
    const SERVER_PASS    = 'ZardoChiri5694';
    const SERVER_SECURE  = 'TLS';
    const SERVER_CHARSET = 'UTF-8';
    
    // sender settings
    const SENDER_EMAIL = self::SERVER_USER;
    const SENDER_NAME  = 'Felizardo Chirindja';
    
    public function __construct(
        private PHPMailer $phpMailer
    ) { }

    public function getEmailError(): string
    {
        return $this->emailError;
    }

    public function sendEmail(
        string $subject,
        string $body,
        string | array $addresses,
        string | array $attachments = [],
        string | array $ccs         = [],
        string | array $bccs        = []
    ): bool
    {
        $this->clearEmailError();

        try {
            $this->phpMailer->isSMTP();
            $this->phpMailer->Host       = self::SERVER_HOST;
            $this->phpMailer->SMTPAuth   = true;
            $this->phpMailer->Username   = self::SERVER_USER;
            $this->phpMailer->Password   = self::SERVER_PASS;
            $this->phpMailer->SMTPSecure = self::SERVER_SECURE;
            $this->phpMailer->Port       = self::SERVER_PORT;
            $this->phpMailer->CharSet    = self::SERVER_CHARSET;

            $this->phpMailer->setFrom(self::SENDER_EMAIL, self::SENDER_NAME);
   
            // add addresses
            $this->addAddresses($addresses);

            // add attachments
            $this->addAttachments($attachments);

            // add ccs
            $ccs = is_array($ccs) ? $ccs : [$ccs];
            foreach ($ccs as $cc) {
                $this->phpMailer->addCC($cc);
            }

            // add bccs
            $bccs = is_array($bccs) ? $bccs : [$bccs];
            foreach ($bccs as $bcc) {
                $this->phpMailer->addBCC($bcc);
            }

            $this->phpMailer->isHTML(true);
            $this->phpMailer->Subject = $subject;
            $this->phpMailer->Body = $body;

            return $this->phpMailer->send();
        } catch (MailException $mailException) {
            $this->error = $mailException->getMessage();
            return false;
        }
    }

    private function addAddresses(string | array $addresses): void
    {
        if (is_array($addresses)) {
            foreach ($addresses as $address) {
                $this->phpMailer->addAddress($address);
            }

            return;
        }

        $this->phpMailer->addAddress($addresses);
    }

    private function addAttachments(string | array $attachments): void
    {
        if (is_array($attachments)) {
            foreach ($attachments as $attachment) {
                $this->phpMailer->addAddress($attachment);
            }

            return;
        }

        $this->phpMailer->addAddress($attachments);
    }
    
    private function clearEmailError(): void
    {
        $this->error = '';
    }
}
