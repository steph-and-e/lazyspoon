<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>reset password</title>
</head>

<body>
    <?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'vendor/autoload.php';

    $mail = new PHPMailer(true);

    try {
        // SMTP Settings
        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'your-email@outlook.com'; // Your Outlook email
        $mail->Password = 'your-app-password'; // Use the App Password from Microsoft
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email Details
        $mail->setFrom('your-email@outlook.com', 'Your Name');
        $mail->addAddress('recipient@example.com'); // Receiver's email
        $mail->Subject = 'Test Email from Outlook SMTP';
        $mail->Body = 'Hello! This is a test email sent using Outlook SMTP.';
        $mail->isHTML(true);

        $mail->send();
        echo '✅ Email sent successfully!';
    } catch (Exception $e) {
        echo "❌ Email failed: {$mail->ErrorInfo}";
    }
    ?>
</body>

</html>