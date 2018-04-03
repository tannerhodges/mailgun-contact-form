<?php

error_reporting(0);
ini_set('display_errors', 0);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  die();
}

require '../vendor/autoload.php';

use Dotenv\Dotenv;
use Mailgun\Mailgun;

$dotenv = new Dotenv(dirname(__DIR__));
$dotenv->load();
$dotenv->required([
  'CONTACT_TO',
  'CONTACT_FROM',
  'CONTACT_SUBJECT',
  'CONTACT_SUCCESS_MESSAGE',
  'MAILGUN_DOMAIN',
  'MAILGUN_API_KEY'
]);

$mg = Mailgun::create(getenv('MAILGUN_API_KEY'));

$response = $mg->messages()->send(getenv('MAILGUN_DOMAIN'), [
  'from'    => $_POST['name'] . ' <' . getenv('CONTACT_FROM') . '>',
  'to'      => getenv('CONTACT_TO'),
  'subject' => getenv('CONTACT_SUBJECT') . ': "' . $_POST['subject'] . '"',
  'text'    => "From: {$_POST['name']} <{$_POST['from']}>
Subject: {$_POST['subject']}

Message Body:
{$_POST['message']}"
]);

echo getenv('CONTACT_SUCCESS_MESSAGE');
