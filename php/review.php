<?php
/**
 * reviews.php
 * Author: Rebecca 
 * Student Number: 
 * Date Created: 2025/04/29
 * 
 * Description:
 * This script handles dish review functionality including:
 * - Processing review form submissions
 * - Validating and sanitizing review data
 * - Storing reviews in the database
 * - Retrieving all reviews for display
 * - Providing user feedback through session messages
 */

// Start session and include database connection
session_start();
include "connect.php";

/**
 * Processes review form submission
 * 
 * Validates and sanitizes input, stores review in database
 * Sets session message with result
 * 
 * @global PDO $dbh Database connection
 * @global array $_POST Review form data
 * @global array $_SESSION Session storage for messages
 */
function processReviewSubmission() {
    global $dbh;
    
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_review'])) {
        // Sanitize and trim all input
        $dish_name = filter_input(INPUT_POST, 'dish_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT, 
                             ['options' => ['min_range' => 1, 'max_range' => 5]]);
        $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        // Validate required fields
        if (empty($dish_name) || empty($first_name) || empty($rating)) {
            $_SESSION['review_message'] = "Please fill in all required fields.";
            header("Location: view_reviews.php");
            exit();
        }
        
        // Prepare and execute SQL statement
        $command = "INSERT INTO reviews (dish_name, first_name, last_name, rating, comment) 
                   VALUES (?, ?, ?, ?, ?)";
        $stmt = $dbh->prepare($command);
        $success = $stmt->execute([$dish_name, $first_name, $last_name, $rating, $comment]);
        
        // Set appropriate session message
        if ($success) {
            $_SESSION['review_message'] = "Thank you for your review!";
        } else {
            $_SESSION['review_message'] = "Error submitting review. Please try again.";
            error_log("Review submission failed: " . implode(", ", $stmt->errorInfo()));
        }
        
        header("Location: view_reviews.php");
        exit();
    }
}

/**
 * Retrieves all reviews from database
 * 
 * @param PDO $dbh Database connection handle
 * @return array All reviews sorted by most recent first
 */
function getAllReviews($dbh) {
    $command = "SELECT * FROM reviews ORDER BY timestamp DESC";
    $stmt = $dbh->prepare($command);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Process review submission if form was posted
processReviewSubmission();