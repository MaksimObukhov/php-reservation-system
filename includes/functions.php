<?php
require_once 'config.php';
require_once 'db.php';
require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function send_confirmation_email($to, $name, $date, $time, $barber_contact) {
  $mailer = new PHPMailer(true);

  try {
    $mailer->isSendmail();

    // Set the recipient and sender email addresses
    $mailer->addAddress($to);
    $mailer->setFrom(EMAIL_FROM);

    // Set the subject and character encoding
    $mailer->CharSet = 'ascii';
    $mailer->Subject = EMAIL_SUBJECT;

    $htmlBody = "<html><head><meta charset=\"utf-8\" /></head><body>
                 Dear $name,<br><br>Your booking for $date at $time has been confirmed.<br>
                 You can also contact your barber: $barber_contact<br>
                 <br>Best regards,<br>Barbershop Team
                 </body></html>";

    // Set email format to HTML
    $mailer->isHTML(true);
    $mailer->Body = $htmlBody;

    $mailer->send();
    echo 'Booking confirmation email has been sent to: ' . htmlspecialchars($to);
  } catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mailer->ErrorInfo}";
  }
}