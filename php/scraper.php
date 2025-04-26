<?php

// Start session
session_start();

// Include other PHP files
include "connect.php";
include "simplify_ingredients.php";

/**
 * Scrape recipe from website
 * @param {String} url
 * @return a PHP associative array of the recipe
 */
function scrapeWebsite($url) {

    // 1. Get recipe data from JSON-LD script tag
    // Load HTML content
    $html = file_get_contents($url); // Reads entire file into a string
    // Create DOMDoucment and suppress warnings
    $dom = new DOMDocument(); // Loads HTML as tree
    @$dom->loadHTML($html); // Suppress warnings caused by malformed HTML
    // Create XPath object
    $xpath = new DOMXPath($dom); // Helps find specific elements in tree
    // Use DOMXPath to find the JSON-LD script tag
    $jsonLdData = null;
    $jsonLdNode = $xpath->query("//script[@type='application/ld+json']");
    if ($jsonLdNode->length > 0) {
        $jsonText = $jsonLdNode->item(0)->nodeValue;
        $jsonLdData = json_decode($jsonText, true);
        // Check if JSON decoding failed
        if (json_last_error() !== JSON_ERROR_NONE) {
            die("JSON Decode Error: " . json_last_error_msg());
        }
    } else {
        die("No JSON-LD found on this page.");
    }
    // If jsonLdData only has one element, set its value to its first element (account for different structures on different sites)
    if (count($jsonLdData)===1) {
        $jsonLdData = $jsonLdData[0];
    }
    //echo var_dump ($jsonLdData);

    // 2. Extract recipe name
    // Get title from "og:title" meta tag
    $titleNode = $xpath->query("//meta[@property='og:title']")->item(0);
    $title = $titleNode ? trim($titleNode->getAttribute("content")) : "";
    // If meta tag does not exist, use the "name" property from JSON-LD
    if (empty($title)) {
        $title = $jsonLdData['name'];
    }
    // If "name" property does not exist, use the title tag
    if (empty($title)) {
        $titleNode = $xpath->query("//title")->item(0);
        $title = $titleNode ? trim($titleNode->getAttribute("content")) : "";
    }

    // 3. Extract recipe URL from "og:url" meta tag
    $URLNode = $xpath->query("//meta[@property='og:url']")->item(0);
    $URL = $URLNode ? trim($URLNode->getAttribute("content")) : "";

    // 4. Extract cover image
    // Get cover image URL from og:image tag
    $imageURLNode = $xpath->query("//meta[@property='og:image']")->item(0);
    $imageURL = $imageURLNode ? trim($imageURLNode->getAttribute("content")) : "";
    // If meta tag does not exist, use the "image"->"url" property from JSON-LD
    if (empty($imageURL)) {
        $imageURL = $jsonLdData['image']['url'] ?? 'No image found';
    }

    // 5. Extract serving size from JSON-LD
    $servingSize = $jsonLdData['recipeYield'] ?? "";
    if (is_array($servingSize)) { // If recipeYield is a list, pick the first element in that list
        $servingSize = $servingSize[0];
    }

    // 6. Extract ingredients from JSON-LD
    $ingredientsList = $jsonLdData['recipeIngredient'] ?? [];

    // 7. Extract instructions as an array of steps from JSON-LD
    $recipeInstructions = $jsonLdData['recipeInstructions'];
    // If $recipeInstructions is a nested structure, flatten it to a 1D array of instructions
    $recipeInstructions = flattenInstructions($recipeInstructions);
    // Extract each step and append it to the instructions array
    if (isset($recipeInstructions) && is_array($recipeInstructions)) {
        foreach ($recipeInstructions as $step) {
            $instructions[] = $step['text'] ?? "No instruction text available";
        }
    }

    // 8. Return PHP associative array of recipe
    return [
        "title" => $title,
        "url" => $URL,
        "coverImage" => $imageURL,
        "servings" => $servingSize,
        "ingredients" => $ingredientsList,
        "commonIngredients"  => simplify_ingredients($ingredientsList),
        "instructions" => $instructions,
    ];
}

/**
 * Flattens the recipe instructions from the JSON-LD tag into a 1D array
 * @param {2D String array} recipeInstructions
 * @return flattenedInstructions
 */
function flattenInstructions($recipeInstructions) {
    // If the structure is nested (array of sections with itemListElement), flatten it
    if (is_array($recipeInstructions) && count($recipeInstructions) > 0 && isset($recipeInstructions[0]['itemListElement']) && is_array($recipeInstructions[0]['itemListElement'])) {
        // Create an empty array to hold the flattened instructions
        $flattenedInstructions = [];
        // Iterate through the sections
        foreach ($recipeInstructions as $section) {
            // Iterate through each step in the section
            if (isset($section['itemListElement']) && is_array($section['itemListElement'])) {
                foreach ($section['itemListElement'] as $step) {
                    // Push the step text to the flattened array
                    if (isset($step['text'])) {
                        $flattenedInstructions[] = $step;
                    }
                }
            }
        }
        return $flattenedInstructions;
    }
    // If not nested, return the original data
    else {
        return $recipeInstructions;
    }
}

///////////////////////////////////////////////////////////////////////////////////////////////

// When admin submits a URL, scrape the recipe from the website
$recipe = null;
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['recipeURL'])) {
    $recipeURL = trim($_POST['recipeURL']);
    $recipe = scrapeWebsite($recipeURL);
    // Add scraped recipe to session
    $_SESSION['scraped_recipe'] = $recipe;
}

// When admin approves scraped recipe, send it to the database
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submitRecipe'])) {
    if (!empty($_SESSION['scraped_recipe'])) {
        $recipe = $_SESSION['scraped_recipe'];

        // 1. Insert the recipe into the database
        $command = "INSERT INTO recipes (title, url, cover_image, servings, ingredients, instructions) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $dbh->prepare($command);
        $params = [
            $recipe['title'],
            $recipe['url'],
            $recipe['coverImage'],
            $recipe['servings'],
            json_encode($recipe['ingredients']),  // JSON encode ingredients list
            json_encode($recipe['instructions'])  // JSON encode instructions list
        ];
        $success = $stmt->execute($params);
        //$recipeId = $dbh->lastInsertId(); // Get the id for this recipe

        // 2. For each common ingredient found, add it to the ingredients list and link it to the recipe
        foreach($recipe['commonIngredients'] as $ingredientName) {
            echo "<p>".$ingredientName."</p>";
            // Check if ingredient exists
            // $command = "SELECT id FROM ingredients WHERE name = ?";
            // $stmt = $dbh->prepare($command);
            // $params = [$ingredientName];
            // $success = $stmt->execute($params);
            // $ingredient = $stmt->fetch(PDO::FETCH_ASSOC);
            // if ($ingredient) {
            //     $ingredientId = $ingredient['id'];
            // }
        //     // Insert new ingredient
        //     else {
        //         $command = "INSERT INTO ingredients (name) VALUES (?)";
        //         $stmt = $dbh->prepare($command);
        //         $params = [$ingredientName];
        //         $success = $stmt->execute($params);
        //         $ingredientId = $dbh->lastInsertId(); // Get the id for this ingredient
        //     }
            
            // 3. Link the recipe and ingredient
            // $command = "INSERT INTO recipe_ingredients (recipe_id, ingredient_id) VALUES (?,?)";
            // $stmt = $dbh->prepare($command);
            // $params = [$recipeId, $ingredientId]; // WHERE AM I GETTING RECIPEID FROM
            // $success = $stmt->execute($params);
        }

        // Clear the session
        unset($_SESSION['scraped_recipe']);
        echo "<p style='color: green;'>Recipe successfully submitted!</p>";
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Scraper</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <h1>Recipe Scraper</h1>
    
    <!-- Recipe Input Form -->
    <form method="post">
        <label for="recipeURL">Enter Recipe URL:</label>
        <input type="text" id="recipeURL" name="recipeURL" required>
        <button type="submit">Fetch Recipe</button>
    </form>

    <!-- Display Recipe Data -->
    <?php if ($recipe): ?>
        <?php if (isset($recipe['error'])): ?>
            <p style="color: red;"><?= htmlspecialchars($recipe['error']) ?></p>
        <?php else: ?>
            <h2>Title:</h2>
            <p><?= htmlspecialchars($recipe['title']) ?></p>

            <h2>Cover Image:</h2>
            <img height="300px" src="<?= htmlspecialchars($recipe['coverImage']) ?>" alt="Recipe Image">

            <h2>Servings:</h2>
            <p><?= htmlspecialchars($recipe['servings']) ?></p>

            <h2>Ingredients:</h2>
            <ul>
                <?php foreach ($recipe['ingredients'] as $ingredient): ?>
                    <li><?= htmlspecialchars($ingredient) ?></li>
                <?php endforeach; ?>
            </ul>

            <h2>Ingredients Found:</h2>
            <ul>
                <?php foreach ($recipe['commonIngredients'] as $commonIngredient): ?>
                    <li><?= htmlspecialchars($commonIngredient) ?></li>
                <?php endforeach; ?>
            </ul>

            <h2>Instructions:</h2>
            <ol>
                <?php foreach ($recipe['instructions'] as $step): ?>
                    <li><?= htmlspecialchars($step) ?></li>
                <?php endforeach; ?>
            </ol>

            <!-- Allow admin to submit recipe to database -->
            <form method="post">
                <button type="submit" name="submitRecipe">Submit Recipe to Database</button>
            </form>

            <!-- Allow admin to cancel recipe scraping (goes back to ___ page) -->

        <?php endif; ?>
    <?php endif; ?>


</body>
</html>