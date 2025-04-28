<?php
/**
 * register.php
 * Author: Mostafa
 * Student Number: 400599915
 * Date Created: 2025/04/24
 * 
 * Description:
 * This page handles user registration with:
 * - Form validation for username, email and password
 * - Secure password hashing
 * - Duplicate account prevention
 * - Immediate session creation upon registration
 * - Visual feedback for form errors
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
include "connect.php";

/**
 * Checks if username already exists in database
 * 
 * @param string $username The username to check
 * @param PDO $dbh Database connection handle
 * @return bool True if username exists, false otherwise
 */
function usernameExists($username, $dbh) {
    $command = "SELECT COUNT(*) FROM users WHERE username = ?";
    $stmt = $dbh->prepare($command);
    $stmt->execute([$username]);
    return $stmt->fetchColumn() > 0;
}

/**
 * Checks if email already exists in database
 * 
 * @param string $email The email to check
 * @param PDO $dbh Database connection handle
 * @return bool True if email exists, false otherwise
 */
function emailExists($email, $dbh) {
    $command = "SELECT COUNT(*) FROM users WHERE email = ?";
    $stmt = $dbh->prepare($command);
    $stmt->execute([$email]);
    return $stmt->fetchColumn() > 0;
}

/**
 * Processes user registration
 * 
 * @param string $email User's email address
 * @param string $username User's chosen username
 * @param string $password User's password
 * @param PDO $dbh Database connection handle
 * @return bool True if registration successful, false otherwise
 */
function register($email, $username, $password, $dbh) {
    // Clear previous message classes
    echo '<script>
    document.querySelectorAll(".inputForm").forEach(el => {
        el.classList.remove("success", "error");
    });
    </script>';

    // Validate inputs
    if (empty($email) || empty($username) || empty($password)) {
        echo "<div class='message-container'><p class='error'>Please fill in all fields.</p></div>";
        echo '<script>
        document.querySelectorAll(".inputForm").forEach(el => {
            el.classList.add("error");
        });
        </script>';
        return false;
    }

    if (emailExists($email, $dbh)) {
        echo "<div class='message-container'><p class='error'>This email is already taken.</p></div>";
        echo '<script>
        document.querySelector("input[name=\"email\"]").closest(".inputForm").classList.add("error");
        </script>';
        return false;
    }

    if (usernameExists($username, $dbh)) {
        echo "<div class='message-container'><p class='error'>This username is already taken.</p></div>";
        echo '<script>
        document.querySelector("input[name=\"username\"]").closest(".inputForm").classList.add("error");
        </script>';
        return false;
    }

    if (strlen($password) < 8) {
        echo "<div class='message-container'><p class='error'>Password must be at least 8 characters.</p></div>";
        echo '<script>
        document.querySelector("input[name=\"password\"]").closest(".inputForm").classList.add("error");
        </script>';
        return false;
    }

    // Hash the password securely
    $hash = password_hash($password, PASSWORD_DEFAULT);
    if ($hash === false) {
        echo "<p class='error'>Failed to secure your password. Please try again.</p>";
        return false;
    }

    try {
        // Insert new user with default 'user' role
        $stmt = $dbh->prepare("INSERT INTO users (username, email, password_hash, `role`) VALUES (?, ?, ?, ?)");
        $success = $stmt->execute([$username, $email, $hash, "user"]);

        if ($success) {
            echo '<script>
            document.querySelectorAll(".inputForm").forEach(el => {
                el.classList.add("success");
            });
            </script>';
            
            // Start secure session
            session_regenerate_id(true);
            
            // Store user data in session
            $_SESSION['user_id'] = $dbh->lastInsertId();
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['role'] = 'user';
            $_SESSION['logged_in'] = true;
            $_SESSION['created_at'] = time();

            echo "<p class='success'>Registration successful! Redirecting to login...</p>";

            // Redirect to search page after 2 seconds
            header("Refresh: 2; url=search.php");
            return true;
        } else {
            echo "<p class='error'>There was an error with the registration. Please try again.</p>";
            return false;
        }
    } catch (PDOException $e) {
        error_log("Registration error: " . $e->getMessage());
        echo "<p class='error'>A system error occurred. Please try again later.</p>";
        return false;
    }
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

    if (!empty($username) && !empty($email) && !empty($password)) {
        register($email, $username, $password, $dbh);
    } else {
        echo "<p class='error'>Please fill in all fields.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/script.js"></script>
</head>
<body>
    <div class="center">
        <div class="form-container">
            <form class="form" method="post" action="register.php">
                <!-- Username Field -->
                <div class="flex-column">
                    <label>Username</label>
                </div>
                <div class="inputForm">
                    <svg height="60" viewBox="-9 32 32" width="40" xmlns="http://www.w3.org/2000/svg">
                        <g id="Layer_3" data-name="Layer 3">
                            <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1z"></path>
                        </g>
                    </svg>
                    <input type="text" class="input" placeholder="Enter your username" name="username" required>
                </div>

                <!-- Email Field -->
                <div class="flex-column">
                    <label>Email</label>
                </div>
                <div class="inputForm">
                    <svg height="20" viewBox="0 0 32 32" width="20" xmlns="http://www.w3.org/2000/svg">
                        <g id="Layer_3" data-name="Layer 3">
                            <path d="m30.853 13.87a15 15 0 0 0 -29.729 4.082 15.1 15.1 0 0 0 12.876 12.918 15.6 15.6 0 0 0 2.016.13 14.85 14.85 0 0 0 7.715-2.145 1 1 0 1 0 -1.031-1.711 13.007 13.007 0 1 1 5.458-6.529 2.149 2.149 0 0 1 -4.158-.759v-10.856a1 1 0 0 0 -2 0v1.726a8 8 0 1 0 .2 10.325 4.135 4.135 0 0 0 7.83.274 15.2 15.2 0 0 0 .823-7.455zm-14.853 8.13a6 6 0 1 1 6-6 6.006 6.006 0 0 1 -6 6z"></path>
                        </g>
                    </svg>
                    <input type="email" class="input" placeholder="Enter your Email" name="email" required>
                </div>

                <!-- Password Field -->
                <div class="flex-column">
                    <label>Password</label>
                </div>
                <div class="inputForm">
                    <svg height="20" viewBox="-64 0 512 512" width="20" xmlns="http://www.w3.org/2000/svg">
                        <path d="m336 512h-288c-26.453125 0-48-21.523438-48-48v-224c0-26.476562 21.546875-48 48-48h288c26.453125 0 48 21.523438 48 48v224c0 26.476562-21.546875 48-48 48zm-288-288c-8.8125 0-16 7.167969-16 16v224c0 8.832031 7.1875 16 16 16h288c8.8125 0 16-7.167969 16-16v-224c0-8.832031-7.1875-16-16-16zm0 0"></path>
                        <path d="m304 224c-8.832031 0-16-7.167969-16-16v-80c0-52.929688-43.070312-96-96-96s-96 43.070312-96 96v80c0 8.832031-7.167969 16-16 16s-16-7.167969-16-16v-80c0-70.59375 57.40625-128 128-128s128 57.40625 128 128v80c0 8.832031-7.167969 16-16 16zm0 0"></path>
                    </svg>
                    <input id="password" type="password" class="input" placeholder="Enter your Password" name="password" required>
                    <span class="toggle-password">
                        <svg class="eye-icon eye-open" viewBox="0 0 24 24" width="20" height="20">
                            <path d="M12 9a3 3 0 0 1 3 3 3 3 0 0 1-3 3 3 3 0 0 1-3-3 3 3 0 0 1 3-3m0-4.5c5 0 9.27 3.11 11 7.5-1.73 4.39-6 7.5-11 7.5S2.73 16.39 1 12c1.73-4.39 6-7.5 11-7.5z" />
                        </svg>
                        <svg class="eye-icon eye-closed" viewBox="0 0 24 24" width="20" height="20" style="display:none;">
                            <path d="M11.83 9L15 12.16V12a3 3 0 0 0-3-3h-.17m-4.3.8l1.55 1.55c-.05.21-.08.42-.08.65a3 3 0 0 0 3 3c.22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53a5 5 0 0 1-5-5c0-.79.2-1.53.53-2.2M2 4.27l2.28 2.28.45.45C3.08 8.3 1.78 10 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.43.42L19.73 22 21 20.73 3.27 3M12 7a5 5 0 0 1 5 5c0 .64-.13 1.26-.36 1.82l2.93 2.93c1.5-1.25 2.7-2.89 3.43-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-4 .7l2.17 2.15C10.74 7.13 11.35 7 12 7z" />
                        </svg>
                    </span>
                </div>

                <button class="button-submit" type="submit">Sign Up</button>
                <p class="p">Already have an account? <span class="span"><a href="login.php">Login</a></span></p>
                
            </form>
        </div>
    </div>
</body>
</html>