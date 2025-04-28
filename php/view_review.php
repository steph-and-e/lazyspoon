<?php

// Start session
session_start();
include 'connect.php';
include 'review.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_review'])) {
    try {
        $stmt = $dbh->prepare("INSERT INTO reviews 
                             (dish_name, first_name, last_name, rating, comment) 
                             VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['dish_name'],
            $_POST['first_name'],
            $_POST['last_name'],
            $_POST['rating'],
            $_POST['comment']
        ]);
        $_SESSION['review_message'] = "Review submitted successfully!";
    } catch (PDOException $e) {
        $_SESSION['review_message'] = "Error: " . $e->getMessage();
    }
    header("Location: view_reviews.php");
    exit();
}

// Get all reviews
$reviews = [];
try {
    $stmt = $dbh->query("SELECT * FROM reviews ORDER BY timestamp DESC");
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error loading reviews: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Dish Reviews</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .review {
            border-bottom: 1px solid #eee;
            padding: 15px 0;
        }

        .error {
            color: red;
        }

        .success {
            color: green;
        }
    </style>
</head>

<body>
    <h1>Dish Reviews</h1>

    <?php if (isset($_SESSION['review_message'])): ?>
        <div class="<?= strpos($_SESSION['review_message'], 'Error') !== false ? 'error' : 'success' ?>">
            <?= $_SESSION['review_message'] ?>
        </div>
        <?php unset($_SESSION['review_message']); ?>
    <?php endif; ?>

    <h2>Submit a Review</h2>
    <form method="post">
        <div class="row">
            <div class="col-12">
                <div class="contact-form-area">
                    <form action="#" method="post">
                        <div class="row">
                            <div class="col-12 col-lg-6">
                                <input type="text" class="form-control" id="name" placeholder="Name">
                            </div>
                            <div class="col-12 col-lg-6">
                                <input type="email" class="form-control" id="email" placeholder="Last Name">
                            </div>
                            <div class="col-12">
                                <input type="text" class="form-control" id="subject" placeholder="Food">
                            </div>
                            <div>
                                <label class="col-12">Rating:
                                    <select name="rating" required>
                                        <option value="5">★★★★★</option>
                                        <option value="4">★★★★☆</option>
                                        <option value="3">★★★☆☆</option>
                                        <option value="2">★★☆☆☆</option>
                                        <option value="1">★☆☆☆☆</option>
                                    </select>
                                </label>
                            </div>
                            <div class="col-12">
                                <textarea name="message" class="form-control" id="message" cols="30" rows="10" placeholder="comment"></textarea>
                            </div>
                            <div class="col-12">
                                <button class="btn delicious-btn mt-30" type="submit">Post Comments</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <p>
            <label>Comment:<br>
                <textarea name="comment" rows="4" required></textarea>
            </label>
        </p>
        <button type="submit" name="submit_review">Submit</button>
    </form>

    <h2>All Reviews</h2>
    <?php if (!empty($reviews)): ?>
        <?php foreach ($reviews as $review): ?>
            <div class="review">
                <h3><?= htmlspecialchars($review['dish_name']) ?></h3>
                <p>By: <?= htmlspecialchars($review['first_name']) ?> <?= htmlspecialchars($review['last_name']) ?></p>
                <p>Rating: <?= str_repeat('★', $review['rating']) ?></p>
                <p><?= htmlspecialchars($review['comment']) ?></p>
                <small><?= date('M j, Y g:i a', strtotime($review['timestamp'])) ?></small>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No reviews yet.</p>
    <?php endif; ?>
</body>

</html>