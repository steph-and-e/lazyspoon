<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include "connect.php";

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);

    if ($email) {
        // Check if email exists
        $stmt = $dbh->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            // Generate secure token
            $token = bin2hex(random_bytes(32));
            $expires = date("Y-m-d H:i:s", time() + 3600); // 1 hour expiration

            // Store token in database
            $updateStmt = $dbh->prepare("UPDATE users SET reset_token = ?, reset_token_expires = ? WHERE user_id = ?");
            $updateStmt->execute([$token, $expires, $user['user_id']]);

            // Create reset link
            $resetLink = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/reset_password_confirm.php?token=$token";

            // Send email using Outlook SMTP
            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.office365.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'lazyspoon1@outlook.com';
                $mail->Password   = 'eybxvyrhzrhgaqve';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                // Recipients
                $mail->setFrom('lazyspoon1@outlook.com', 'lazyspoon');
                $mail->addAddress($email);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Password Reset Request';
                $mail->Body    = "
                    <h2>Password Reset</h2>
                    <p>We received a request to reset your password. Click the button below to proceed:</p>
                    <a href='$resetLink' style='background:#007bff;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;display:inline-block;margin:10px 0;'>
                        Reset Password
                    </a>
                    <p>This link will expire in 1 hour.</p>
                    <p style='color:#666;font-size:0.9em;'>If you didn't request this, please ignore this email or contact support if you have concerns.</p>
                ";

                $mail->SMTPDebug = 3; // Shows detailed connection logs
                $mail->Debugoutput = function ($str, $level) {
                    error_log("SMTP: $str"); // Logs to your server's error log
                };
                $mail->send();
                $success = "If an account with that email exists, we've sent a password reset link.";
            } catch (Exception $e) {
                error_log("Mailer Error: " . $mail->ErrorInfo);
                error_log("Reset attempt for: " . $email); // Log which email failed
                $error = "We couldn't send the reset email. Please try again later.";
            }
        } else {
            $success = "If an account with that email exists, we've sent a password reset link.";
        }
    } else {
        $error = "Please enter a valid email address.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .reset-container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            margin-top: 0;
            color: #333;
            text-align: center;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }

        input[type="email"] {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }

        button {
            background: #007bff;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            width: 100%;
            transition: background 0.3s;
        }

        button:hover {
            background: #0056b3;
        }

        .alert {
            padding: 0.75rem;
            margin-bottom: 1rem;
            border-radius: 4px;
        }

        .alert.success {
            background: #d4edda;
            color: #155724;
        }

        .alert.error {
            background: #f8d7da;
            color: #721c24;
        }

        .login-link {
            text-align: center;
            margin-top: 1rem;
        }
    </style>
</head>

<body>
    <div class="reset-container">
        <h2>Reset Password</h2>

        <?php if ($error): ?>
            <div class="alert error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>
            <button type="submit">Send Reset Link</button>
        </form>

        <div class="login-link">
            Remember your password? <a href="login.php">Log in</a>
        </div>
    </div>
</body>

</html>