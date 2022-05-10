<?php

namespace MailSender;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as MailException;

class MailSender
{
    private string $emailError;
    
    // Server settings
    const SERVER_SECURE  = 'TLS';
    private string $host;
    private int    $port;
    private string $charset;
    
    // sender settings
    const SENDER_NAME  = 'Felizardo Chirindja';
    
    public function __construct(
        private PHPMailer $phpMailer,
        private string    $senderEmail,
        private string    $senderUsername,
        private string    $senderPassword
    ) { }

    public function setHost(string $host): self
    {
        $this->host = $host;
        return $this;
    }

    public function setPort(int $port): self
    {
        $this->port = $port;
        return $this;
    }

    public function setCharset(string $charset): self
    {
        $this->charset = $charset;
        return $this;
    }

    public function getEmailError(): string
    {
        return $this->emailError;
    }

    public function sendEmail(
        string $subject,
        string $body,
        string | array $addresses,
        string | array $attachments       = [],
        string | array $carbonCopies      = [],
        string | array $blindCarbonCopies = []
    ): bool
    {
        $this->clearEmailError();

        try {
            $this->phpMailer->isSMTP();
            $this->phpMailer->Host       = $this->host;
            $this->phpMailer->SMTPAuth   = true;
            $this->phpMailer->Username   = $this->senderEmail;
            $this->phpMailer->Password   = $this->senderPassword;
            $this->phpMailer->SMTPSecure = self::SERVER_SECURE;
            $this->phpMailer->Port       = $this->port;
            $this->phpMailer->CharSet    = $this->charset;

            $this->phpMailer->setFrom($this->senderEmail, $this->senderUsername);
            
            $this->addAddresses($addresses);
            $this->addAttachments($attachments);
            $this->addCarbonCopies($carbonCopies);
            $this->addBlindCarbonCopies($blindCarbonCopies);

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

    private function addCarbonCopies(string | array $carbonCopies): void
    {
        if (is_array($carbonCopies)) {
            foreach ($carbonCopies as $carbonCopy) {
                $this->phpMailer->addCC($carbonCopy);
            }

            return;
        }

        $this->phpMailer->addAddress($carbonCopies);
    }

    private function addAttachments(string | array $attachments): void
    {
        if (is_array($attachments)) {
            foreach ($attachments as $attachment) {
                $this->phpMailer->addAttachment($attachment);
            }

            return;
        }

        $this->phpMailer->addAddress($attachments);
    }

    private function addBlindCarbonCopies(string | array $blindCarbonCopies): void
    {
        if (is_array($blindCarbonCopies)) {
            foreach ($blindCarbonCopies as $blindCarbonCopy) {
                $this->phpMailer->addAttachment($blindCarbonCopy);
            }

            return;
        }

        $this->phpMailer->addAddress($blindCarbonCopies);
    }
    
    private function clearEmailError(): void
    {
        $this->error = '';
    }
}
