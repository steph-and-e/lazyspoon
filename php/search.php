<?php

/**
 * search.php
 * Author: Stephanie
 * Student Number: 400562559
 * Date Created: 2025/04/24
 * 
 * Description: 
 * This file handles recipe searching functionality. It allows authenticated users to:
 * - Search for recipes by ingredients (with autocomplete suggestions)
 * - View matching recipes with their details
 * - Access recipe reviews
 * The page maintains user session and provides logout functionality.
 */

// Start the session and retrieve username
session_start();
$username = $_SESSION['username'];

// Include the database connection
include "connect.php";

/**
 * Processes the search form and finds recipes containing all specified ingredients
 * 
 * @global PDO $dbh Database connection handle
 * @return array Returns an array of matching recipes or empty array if none found
 */
function searchRecipesByIngredients()
{
    global $dbh;
    $searchResults = [];

    if ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET['ingredients'])) {
        $ingredients = array_map('trim', explode(',', $_GET['ingredients']));
        $placeholders = implode(',', array_fill(0, count($ingredients), '?'));

        $command = "
            SELECT r.id, r.title, r.url, r.cover_image
            FROM recipes r
            INNER JOIN recipe_ingredients ri ON r.id = ri.recipe_id
            INNER JOIN ingredients i ON ri.ingredient_id = i.id
            WHERE i.name IN ($placeholders)
            GROUP BY r.id
            HAVING COUNT(DISTINCT i.name) = ?
        ";

        $stmt = $dbh->prepare($command);
        $params = array_merge($ingredients, [count($ingredients)]);
        $stmt->execute($params);
        $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    return $searchResults;
}

// Perform search if ingredients were submitted
$searchResults = searchRecipesByIngredients();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Search</title>
    <link rel="stylesheet" href="../css/style2.css">
</head>

<body>

    <!-- Profile and logout button -->
    <div class="profile-container">
        <p id="username"><?= htmlspecialchars($username) ?></p>
        <a href="logout.php" class="logout-button">Logout</a>
    </div>

    <!-- Search for ingredients with autocomplete -->
    <h1>USER: Recipe Search</h1>
    <form method="get" action="search.php">
        <label for="ingredients">Enter Ingredients (start typing):</label>
        <input type="text" id="ingredients" name="ingredients" autocomplete="off" required>
        <ul id="suggestions-list" style="display:none;"></ul>
        <button type="submit">Search Recipes</button>
    </form>

    <!-- Display each matched recipe on the page -->
    <?php if (!empty($searchResults)): ?>
        <h2>Recipes Found</h2>
        <ul>
            <?php foreach ($searchResults as $recipe): ?>
                <li>
                    <h3><a href="<?= htmlspecialchars($recipe['url']) ?>"><?= htmlspecialchars($recipe['title']) ?></a></h3>
                    <img src="<?= htmlspecialchars($recipe['cover_image']) ?>" alt="Recipe Image" height="100">
                    <a href="view_review.php?recipe_title=<?= urlencode($recipe['title']) ?>">View/Add Reviews</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php elseif ($_SERVER["REQUEST_METHOD"] == "GET"): ?>
        <p>No recipes found with those ingredients.</p>
    <?php endif; ?>

    <script src="../js/search.js"></script>
</body>

</html>