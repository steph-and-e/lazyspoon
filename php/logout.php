<?php
/**
 * logout.php
 * Author: Mostafa
 * Student Number: 400599915
 * Date Created: 2025/04/24
 * 
 * Description:
 * This script handles user logout functionality by:
 * - Terminating the current session
 * - Clearing all session data
 * - Redirecting to the login page
 * - Implementing secure session destruction
 */

// Start the session to access session variables
session_start();

// Clear all session variables
session_unset();

// Destroy the session completely
// This removes the session ID and deletes session data from the server
session_destroy();

// Redirect to login page with 302 temporary redirect
header("Location: login.php");

// Ensure no further code is executed after redirect
exit();