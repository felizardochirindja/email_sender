<?php

require '../vendor/autoload.php';

use App\Email;

$addresses = 'felixchirindza30@gmail.com';
$subject   = 'Teste de email';
$body      = '<h1>Teste de email <strong>body</strong></h1>';

$mailer = new Email();
$sucess = $mailer->sendEmail($subject, $body, $addresses);

echo $sucess ? 'Email enviado com sucesso' : $mailer->getEmailError();
