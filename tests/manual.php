<?php

require '../vendor/autoload.php';

use MailSender\MailSender;
use PHPMailer\PHPMailer\PHPMailer;

$addresses = 'felixchirindza30@gmail.com';
$subject   = 'Teste de email';
$body      = '<h1>Teste de email <strong>body</strong></h1>';

$mailer = new MailSender(new PHPMailer());
$sucess = $mailer->sendEmail($subject, $body, $addresses);

echo $sucess ? 'Email enviado com sucesso' : $mailer->getEmailError();
