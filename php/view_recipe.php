<?php
include 'connect.php';
session_start();

// Get the recipe ID from the URL
$recipe_id = $_GET['recipe_id'] ?? null;

if (!$recipe_id) {
    echo "No recipe selected.";
    exit();
}

// Fetch the recipe details
$query = $conn->prepare("SELECT * FROM recipes WHERE id = ?");
$query->bind_param("i", $recipe_id);
$query->execute();
$result = $query->get_result();
$recipe = $result->fetch_assoc();

if (!$recipe) {
    echo "Recipe not found.";
    exit();
}

//success message
if (isset($_GET['message']) && $_GET['message'] === 'review_submitted') {
    echo "<p style='color: green;'>Review submitted successfully!</p>";
}
if (isset($_GET['message']) && $_GET['message'] === 'remix_submitted') {
    echo "<p style='color: green;'>Remix submitted successfully and awaiting approval!</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($recipe['title']); ?></title>
</head>
<body>

    <h1><?php echo htmlspecialchars($recipe['title']); ?></h1>
    <h3>Ingredients:</h3>
    <p><?php echo nl2br(htmlspecialchars($recipe['ingredients'])); ?></p>

    <h3>Instructions:</h3>
    <p><?php echo nl2br(htmlspecialchars($recipe['instructions'])); ?></p>

    <br><br>

    <!-- Remix Button -->
    <a href="recipe.php?recipe_id=<?php echo $recipe_id; ?>">Remix this Recipe</a>

    <hr>

    <!-- Review Form -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <h3>Leave a Review:</h3>
        <form action="submit_review.php" method="POST">
            <input type="hidden" name="recipe_id" value="<?php echo $recipe['id']; ?>">
            
            <label>Rating (1-5):</label><br>
            <input type="number" name="rating" min="1" max="5" required><br><br>

            <label>Comment:</label><br>
            <textarea name="comment" rows="4" cols="50" required></textarea><br><br>

            <input type="submit" value="Submit Review">
        </form>
    <?php else: ?>
        <p><em>You must be logged in to leave a review.</em></p>
    <?php endif; ?>

    <hr>

    <!-- Show Reviews -->
    <h3>Reviews:</h3>
    <?php
    $review_query = $conn->prepare("SELECT rating, comment, timestamp FROM reviews WHERE recipe_id = ?");
    $review_query->bind_param("i", $recipe['id']);
    $review_query->execute();
    $review_result = $review_query->get_result();

    if ($review_result->num_rows > 0) {
        while ($review = $review_result->fetch_assoc()) {
            echo "<p><strong>Rating:</strong> " . htmlspecialchars($review['rating']) . "/5<br>";
            echo "<strong>Comment:</strong> " . nl2br(htmlspecialchars($review['comment'])) . "<br>";
            echo "<em>Posted at: " . $review['timestamp'] . "</em></p><hr>";
        }
    } else {
        echo "<p>No reviews yet. Be the first!</p>";
    }
    ?>

    <hr>

    <!-- Show Remix History -->
    <?php include('remix_history.php'); ?>

</body>
</html>

