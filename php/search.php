<?php
// Include the database connection
include "connect.php";

// Process the search form submission
$searchResults = [];
if ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET['ingredients'])) {
    // Sanitize input
    $ingredients = array_map('trim', explode(',', $_GET['ingredients']));

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
    <style>
        #suggestions-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
            background-color: white;
            border: 1px solid #ccc;
            position: absolute;
            max-height: 150px;
            overflow-y: auto;
            width: 100%;
        }

        #suggestions-list li {
            padding: 8px;
            cursor: pointer;
        }

        #suggestions-list li:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <h1>Recipe Search</h1>
    <!-- Search Form -->
    <form method="get" action="search.php">
        <label for="ingredients">Enter Ingredients (start typing):</label>
        <input type="text" id="ingredients" name="ingredients" autocomplete="off" required>
        <ul id="suggestions-list" style="display:none;"></ul>
        <button type="submit">Search Recipes</button>
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
    <?php elseif ($_SERVER["REQUEST_METHOD"] == "GET"): ?>
        <p>No recipes found with those ingredients.</p>
    <?php endif; ?>

    <script>
        document.getElementById("ingredients").addEventListener("input", function() {
            const query = this.value;
            const suggestionsList = document.getElementById("suggestions-list");

            if (query.length > 0) {
                // Perform AJAX request
                fetch(`autocomplete.php?query=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        suggestionsList.innerHTML = '';
                        if (data.length > 0) {
                            suggestionsList.style.display = 'block';
                            data.forEach(item => {
                                const li = document.createElement("li");
                                li.textContent = item.name;
                                li.addEventListener('click', () => {
                                    document.getElementById("ingredients").value = item.name;
                                    suggestionsList.style.display = 'none';
                                });
                                suggestionsList.appendChild(li);
                            });
                        } else {
                            suggestionsList.style.display = 'none';
                        }
                    });
            } else {
                suggestionsList.style.display = 'none';
            }
        });
    </script>
</body>
</html>
