<?php

/**
 * view_review.php
 * Author: Rebecca
 * Student Number: 
 * Date Created: 2025/04/30
 * 
 * Description:
 * This page handles the display and submission of recipe reviews.
 */

session_start();
include 'connect.php';

//Get user_id from session
if (!isset($_SESSION['user_id'])) {
    $_SESSION['review_message'] = "Please log in to submit reviews.";
    header("Location: login.php");
    exit();
}

// Get the recipe title from URL parameter
$recipe_title = isset($_GET['recipe_title']) ? urldecode($_GET['recipe_title']) : '';

/**
 * Handles review form submission
 */
function handleReviewSubmission($recipe_title)
{
    global $dbh;

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_review'])) {
        try {
            // Validate required fields
            if (empty($recipe_title)) {
                throw new Exception("Recipe title is required");
            }
            if (empty($_POST['first_name'])) {
                throw new Exception("First name is required");
            }

            $rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT, [
                'options' => ['min_range' => 1, 'max_range' => 5]
            ]);
            if ($rating === false) {
                throw new Exception("Please select a valid rating (1-5 stars)");
            }

            // Get the logged-in user's ID from session
            $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

            // Get recipe_id from URL if available (you'll need to pass this from search.php)
            $recipe_id = isset($_GET['recipe_id']) ? (int)$_GET['recipe_id'] : 0;

            // Prepare and execute SQL
            $stmt = $dbh->prepare("INSERT INTO reviews 
            (recipe_title, user_id, first_name, last_name, rating, comment) 
            VALUES (?, ?, ?, ?, ?, ?)");

            $stmt->execute([
                htmlspecialchars(trim($recipe_title)),
                $user_id,
                htmlspecialchars(trim($_POST['first_name'])),
                htmlspecialchars(trim($_POST['last_name'] ?? '')),
                intval($_POST['rating']),
                htmlspecialchars(trim($_POST['comment'] ?? ''))
            ]);


            $_SESSION['review_message'] = "Review submitted successfully!";
        } catch (Exception $e) {
            $_SESSION['review_message'] = $e->getMessage();
        }

        // Redirect back to same page to show the message
        $redirect_url = "view_review.php?recipe_title=" . urlencode($recipe_title);
        if (isset($_GET['recipe_id'])) {
            $redirect_url .= "&recipe_id=" . (int)$_GET['recipe_id'];
        }
        header("Location: $redirect_url");
        exit();
    }
}

/**
 * Gets reviews filtered by recipe if specified
 */
function getReviews($recipe_title = '')
{
    global $dbh;
    try {
        if (!empty($recipe_title)) {
            $stmt = $dbh->prepare("SELECT * FROM reviews 
                                 WHERE recipe_title = ? 
                                 ORDER BY timestamp DESC");
            $stmt->execute([$recipe_title]);
        } else {
            $stmt = $dbh->query("SELECT * FROM reviews ORDER BY timestamp DESC");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error loading reviews: " . $e->getMessage());
        return [];
    }
}

// Process form submission
handleReviewSubmission($recipe_title);

// Get reviews (filtered by recipe if specified)
$reviews = getReviews($recipe_title);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= !empty($recipe_title) ? htmlspecialchars($recipe_title) . " Reviews" : "Dish Reviews" ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            line-height: 1.6;
        }

        .review {
            border-bottom: 1px solid #eee;
            padding: 20px 0;
        }

        .message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }

        .error {
            background-color: #ffebee;
            color: #c62828;
        }

        .success {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        textarea {
            min-height: 100px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 4px;
        }

        .rating-stars {
            color: #FFD700;
            font-size: 1.2em;
        }

        .timestamp {
            color: #777;
            font-size: 0.9em;
            font-style: italic;
        }
    </style>
</head>

<body>
    <h1><?= !empty($recipe_title) ? htmlspecialchars($recipe_title) . " Reviews" : "Dish Reviews" ?></h1>

    <?php if (isset($_SESSION['review_message'])): ?>
        <div class="message <?= strpos($_SESSION['review_message'], 'Error') !== false ? 'error' : 'success' ?>">
            <?= htmlspecialchars($_SESSION['review_message']) ?>
        </div>
        <?php unset($_SESSION['review_message']); ?>
    <?php endif; ?>

    <section>
        <h2>Submit a Review</h2>
        <form method="post">
            <div class="form-group">
                <label>Recipe Title *</label>
                <input type="text" value="<?= htmlspecialchars($recipe_title) ?>" readonly disabled>

            </div>

            <div class="form-group">
                <label for="first_name">First Name *</label>
                <input type="text" id="first_name" name="first_name" required
                    value="<?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : '' ?>">
            </div>

            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name">
            </div>

            <div class="form-group">
                <label for="rating">Rating *</label>
                <select id="rating" name="rating" required>
                    <option value="">Select a rating</option>
                    <option value="5">★★★★★ Excellent</option>
                    <option value="4">★★★★☆ Very Good</option>
                    <option value="3">★★★☆☆ Good</option>
                    <option value="2">★★☆☆☆ Fair</option>
                    <option value="1">★☆☆☆☆ Poor</option>
                </select>
            </div>

            <div class="form-group">
                <label for="comment">Your Review *</label>
                <textarea id="comment" name="comment" required></textarea>
            </div>

            <button type="submit" name="submit_review">Submit Review</button>
        </form>
    </section>

    <section>
        <h2><?= !empty($recipe_title) ? htmlspecialchars($recipe_title) . " Reviews" : "All Reviews" ?></h2>

        <?php if (!empty($reviews)): ?>
            <?php foreach ($reviews as $review): ?>
                <article class="review">
                    <h3><?= htmlspecialchars($review['recipe_title']) ?></h3>
                    <p>Review by: <?= htmlspecialchars($review['first_name']) ?>
                        <?= !empty($review['last_name']) ? htmlspecialchars($review['last_name']) : '' ?></p>

                    <div class="rating-stars">
                        <?= str_repeat('★', $review['rating']) ?>
                        <?= str_repeat('☆', 5 - $review['rating']) ?>
                    </div>

                    <p><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                    <p class="timestamp">Posted on: <?= date('F j, Y \a\t g:i a', strtotime($review['timestamp'])) ?></p>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No reviews yet. Be the first to review!</p>
        <?php endif; ?>
    </section>
    <img src=/img/lazyspoon.png" alt="">
</body>

</html>