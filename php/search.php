<?php
session_start();
include "connect.php";

// Process the search form submission
$searchResults = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['ingredients'])) {
    // Sanitize input
    $ingredients = array_map('trim', explode(',', $_POST['ingredients']));

    // 1. Fetch recipes based on ingredients
    $placeholders = implode(',', array_fill(0, count($ingredients), '?'));
    $command = "
        SELECT r.id, r.title, r.url, r.cover_image
        FROM recipes r
        INNER JOIN recipe_ingredients ri ON r.id = ri.recipe_id
        INNER JOIN ingredients i ON ri.ingredient_id = i.id
        WHERE i.name IN ($placeholders)
        GROUP BY r.id
        HAVING COUNT(DISTINCT i.name) = ?  -- Match all ingredients
    ";
    $stmt = $dbh->prepare($command);
    $params = array_merge($ingredients, [count($ingredients)]);
    $stmt->execute($params);

    $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Search</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

    <h1>Search Recipes</h1>

    <!-- Search Form -->
    <form method="post">
        <label for="ingredients">Enter Ingredients (comma separated):</label>
        <input type="text" id="ingredients" name="ingredients" required>
        <button type="submit">Search</button>
    </form>

    <!-- Display Search Results -->
    <?php if (!empty($searchResults)): ?>
        <h2>Recipes Found</h2>
        <ul>
            <?php foreach ($searchResults as $recipe): ?>
                <li>
                    <h3><a href="<?= htmlspecialchars($recipe['url']) ?>"><?= htmlspecialchars($recipe['title']) ?></a></h3>
                    <img src="<?= htmlspecialchars($recipe['cover_image']) ?>" alt="Recipe Image" height="100">
                </li>
            <?php endforeach; ?>
        </ul>
    <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <p>No recipes found with those ingredients.</p>
    <?php endif; ?>

</body>
</html>
