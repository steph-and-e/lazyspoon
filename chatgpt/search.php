<?php
session_start();
include 'connect.php';

// Process the search form submission
$searchResults = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['ingredients'])) {
    // Sanitize input
    $ingredients = array_map('trim', explode(',', $_POST['ingredients']));

    // Fetch recipes based on ingredients
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
            z-index: 1000;
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
    <form method="post" action="search.php" autocomplete="off" style="position:relative;">
        <label for="ingredients">Enter Ingredients (comma separated, start typing!):</label><br>
        <input type="text" id="ingredients" name="ingredients" required>
        <ul id="suggestions-list" style="display:none;"></ul>
        <button type="submit">Search Recipes</button>
    </form>

    <br>

    <!-- Display Search Results -->
    <?php if (!empty($searchResults)): ?>
        <h2>Recipes Found</h2>
        <ul>
            <?php foreach ($searchResults as $recipe): ?>
                <li>
                    <h3><a href="<?= htmlspecialchars($recipe['url']) ?>" target="_blank"><?= htmlspecialchars($recipe['title']) ?></a></h3>
                    <?php if (!empty($recipe['cover_image'])): ?>
                        <img src="<?= htmlspecialchars($recipe['cover_image']) ?>" alt="Recipe Image" height="100">
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <p>No recipes found with those ingredients.</p>
    <?php endif; ?>

    <script>
        document.getElementById("ingredients").addEventListener("input", function() {
            const query = this.value.split(',').pop().trim();  // Get the last typed word
            const suggestionsList = document.getElementById("suggestions-list");

            if (query.length > 0) {
                // Perform AJAX request
                fetch(`autocomplete.php?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        suggestionsList.innerHTML = '';
                        if (data.length > 0) {
                            suggestionsList.style.display = 'block';
                            data.forEach(item => {
                                const li = document.createElement("li");
                                li.textContent = item.name;
                                li.addEventListener('click', () => {
                                    let currentInput = document.getElementById("ingredients").value;
                                    let parts = currentInput.split(',');
                                    parts[parts.length - 1] = item.name; // Replace last typed part
                                    document.getElementById("ingredients").value = parts.join(', ') + ', ';
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

        document.addEventListener('click', function(event) {
            const suggestionsList = document.getElementById("suggestions-list");
            if (!document.getElementById("ingredients").contains(event.target)) {
                suggestionsList.style.display = 'none';
            }
        });
    </script>

</body>
</html>
