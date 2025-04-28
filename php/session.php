<?php
/**
 * session.php
 * Author: Mostafa
 * Student Number: 400599915
 * Date Created: 2025/04/26
 * 
 * Description:
 * This file handles session management and security for the application.
 * It includes functions for session validation, timeout handling, and role checking.
 * Implements security best practices including strict session settings and timeout protection.
 */

// Enable secure session settings
ini_set('session.cookie_httponly', 1);  // Prevent JavaScript access to session cookie
ini_set('session.use_strict_mode', 1);   // Prevent session fixation attacks
session_start();

// Session timeout constant (30 minutes)
const SESSION_TIMEOUT = 1800;

/**
 * Checks if a user is currently logged in with a valid session
 * 
 * Validates both session variables and session timeout
 * Automatically updates last activity timestamp on successful validation
 * 
 * @return bool Returns true if user is logged in with valid session, false otherwise
 */
function is_logged_in() {
    // Check required session variables
    if (empty($_SESSION['user_id']) || empty($_SESSION['logged_in'])) {
        return false;
    }
    
    // Check session timeout
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT)) {
        session_unset();    // Clear all session variables
        session_destroy();  // Destroy the session
        return false;
    }
    
    // Update last activity timestamp
    $_SESSION['last_activity'] = time();
    return true;
}

/**
 * Enforces login requirement for protected pages
 * 
 * Redirects to login page if user is not authenticated
 * Immediately terminates script execution after redirect
 */
function require_login() {
    if (!is_logged_in()) {
        header("Location: login.php");
        exit();  // Prevent further execution
    }
}

/**
 * Checks if the current user has admin privileges
 * 
 * @return bool Returns true if user is logged in and has admin role, false otherwise
 */
function is_admin() {
    return is_logged_in() && $_SESSION['role'] === 'admin';
}