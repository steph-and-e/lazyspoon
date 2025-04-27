<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require 'vendor/autoload.php';
include "connect.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

$error = '';
$success = '';
$show_form = false;

// Check if token exists in URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Verify token with database
    $stmt = $dbh->prepare("SELECT user_id, reset_token_expires FROM users WHERE reset_token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch();
    
    if ($user) {
        // Check if token is expired
        $now = date("Y-m-d H:i:s");
        if ($user['reset_token_expires'] > $now) {
            $show_form = true;
            
            // Process password update
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $new_password = $_POST['password'];
                $confirm_password = $_POST['confirm_password'];
                
                // Validate passwords
                if (empty($new_password)) {
                    $error = "Password cannot be empty";
                } elseif (strlen($new_password) < 8) {
                    $error = "Password must be at least 8 characters";
                } elseif ($new_password !== $confirm_password) {
                    $error = "Passwords do not match";
                } else {
                    // Hash the new password
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    
                    // Update password and clear reset token
                    $updateStmt = $dbh->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expires = NULL WHERE user_id = ?");
                    if ($updateStmt->execute([$hashed_password, $user['user_id']])) {
                        $success = "Your password has been updated successfully!";
                        $show_form = false;
                    } else {
                        $error = "Failed to update password. Please try again.";
                    }
                }
            }
        } else {
            $error = "This reset link has expired. Please request a new one.";
        }
    } else {
        $error = "Invalid reset token. Please check the link or request a new one.";
    }
} else {
    $error = "No reset token provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password Confirmation</title>
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
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
        input[type="password"] {
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
            <div class="login-link">
                You can now <a href="login.php">log in</a> with your new password.
            </div>
        <?php elseif ($show_form): ?>
            <form method="POST">
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password" required minlength="8">
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required minlength="8">
                </div>
                <button type="submit">Update Password</button>
            </form>
        <?php else: ?>
            <div class="login-link">
                <a href="reset_password.php">Request new password reset</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>