<?php
include 'connect.php';
session_start();

// get recipe ID from URL
$original_id = $_GET['recipe_id'] ?? null;

if ($original_id) {
    $query = $conn->prepare("SELECT * FROM recipes WHERE recipe_id = ?");
    $query->bind_param("i", $original_id);
    $query->execute();
    $result = $query->get_result();
    $recipe = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Remix Recipe</title>

</head>
<body>
    <h2>Remix: <?php echo htmlspecialchars($recipe['title']); ?></h2>
    <form action="remix_form.php" method="POST" onsubmit="return validateRemixForm();">
        <input type="hidden" name="original_recipe_id" value="<?php echo $original_id; ?>">
        <label>Title:</label><br>
        <input type="text" name="title" value="<?php echo htmlspecialchars($recipe['title']); ?>"><br><br>

        <label>Ingredients:</label><br>
        <textarea name="ingredients"><?php echo htmlspecialchars($recipe['ingredients']); ?></textarea><br><br>

        <label>Instructions:</label><br>
        <textarea name="instructions"><?php echo htmlspecialchars($recipe['instructions']); ?></textarea><br><br>

        <input type="submit" value="Submit Remix">
    </form>
</body>
</html>
