<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$response = array();

// Apply some basic validation and strip HTML tags.
if (array_key_exists('message', $_POST) && array_key_exists('name', $_POST)) {
    $message = substr(strip_tags($_POST['message']), 0, 16384);
    $name = substr(strip_tags($_POST['name']), 0, 255);
} else {
    $response['status'] = 'error';
    $response['message'] = 'Hiányzó adatok.';
    echo json_encode($response);
    return;
}

// Make sure the provided address is valid.
if (array_key_exists('email', $_POST) && PHPMailer::validateAddress($_POST['email'])) {
    $email = $_POST['email'];
} else {
    $response['status'] = 'error';
    $response['message'] = 'Érvénytelen e-mail cím.';
    echo json_encode($response);
    return;
}

try {
    $mail = new PHPMailer();
    $mail->CharSet    = "UTF-8";
    $mail->IsSMTP();
    $mail->Mailer     = "smtp";
    $mail->SMTPAuth   = TRUE;
    $mail->SMTPSecure = "tls";
    $mail->Port       = 587;
    $mail->Host       = "smtp.gmail.com";
    $mail->Username   = 'balazs.flystraight.media@gmail.com';
    $mail->Password   = 'kqktvixoyjzakhon';
    $mail->setFrom('noreply@flystraight.media', $name);
    $mail->addAddress('hello@flystraight.media');
    $mail->addReplyTo($email, $name);
    $mail->Subject = 'Kapcsolat';
    $mail->Body = $message;
    $mail->send();
    $response['status'] = 'success';
    $response['message'] = 'Köszönjük! Mihamarabb keresni fogunk.';
    echo json_encode($response);
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = "Hiba történt. Kérjük keress minket az e-mail címünkön közvetlenül. {$mail->ErrorInfo}";
    echo json_encode($response);
}
?>