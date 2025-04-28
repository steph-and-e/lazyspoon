<?php

/**
 * reset_password.php
 * Author: Mostafa
 * Student Number: 400599915
 * Date Created: 2025/04/23
 * 
 * Description:
 * This file handles password reset requests by:
 * - Validating user email addresses
 * - Generating secure reset tokens
 * - Sending password reset links via email
 * - Implementing security measures against timing attacks
 */

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Load PHPMailer and database connection
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include "connect.php";

// Initialize messages
$error = '';
$success = '';

/**
 * Processes password reset request
 * 
 * Validates email, generates secure token, stores in database, and sends reset email
 * 
 * @global PDO $dbh Database connection
 * @global string $error Error message
 * @global string $success Success message
 */
function processResetRequest()
{
    global $dbh, $error, $success;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);

        if ($email) {
            // Check if email exists (using prepared statement)
            $stmt = $dbh->prepare("SELECT user_id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user) {
                // Generate cryptographically secure token
                $token = bin2hex(random_bytes(32));
                $expires = date("Y-m-d H:i:s", time() + 3600); // 1 hour expiration

                // Store token in database
                $updateStmt = $dbh->prepare("UPDATE users SET reset_token = ?, reset_token_expires = ? WHERE user_id = ?");
                $updateStmt->execute([$token, $expires, $user['user_id']]);

                // Create secure reset link
                $resetLink = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/reset_password_confirm.php?token=$token";

                // Send reset email
                sendResetEmail($email, $resetLink);
            }

            // Always show success message to prevent email enumeration
            $success = "If an account with that email exists, we've sent a password reset link.";
        } else {
            $error = "Please enter a valid email address.";
        }
    }
}

/**
 * Sends password reset email using PHPMailer
 * 
 * @param string $email Recipient email address
 * @param string $resetLink Generated password reset link
 * @throws Exception If email sending fails
 */
function sendResetEmail($email, $resetLink)
{
    $mail = new PHPMailer(true);

    try {
        // Server settings for Outlook SMTP
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

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request';
        $mail->Body    = createEmailBody($resetLink);

        // Debugging
        $mail->SMTPDebug = 3;
        $mail->Debugoutput = function ($str, $level) {
            error_log("SMTP: $str");
        };

        $mail->send();
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
        error_log("Reset attempt for: " . $email);
        throw new Exception("Email sending failed");
    }
}

/**
 * Creates HTML email body for password reset
 * 
 * @param string $resetLink Generated reset link
 * @return string Formatted HTML email content
 */
function createEmailBody($resetLink)
{
    return "
        <h2>Password Reset</h2>
        <p>We received a request to reset your password. Click the button below to proceed:</p>
        <a href='$resetLink' style='background:#007bff;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;display:inline-block;margin:10px 0;'>
            Reset Password
        </a>
        <p>This link will expire in 1 hour.</p>
        <p style='color:#666;font-size:0.9em;'>If you didn't request this, please ignore this email or contact support if you have concerns.</p>
    ";
}

// Process the reset request
processResetRequest();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password Request</title>
    <!-- CSS styles remain unchanged -->
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