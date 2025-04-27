<?php
// Include database connection
include 'connect.php';

// Get the query parameter from the AJAX request
$query = isset($_GET['query']) ? trim($_GET['query']) : '';

// Prepare and execute the SQL query
if ($query) {
    $command = "SELECT name FROM ingredients WHERE name LIKE :query LIMIT 5";  // Limit to 5 suggestions
    $stmt = $dbh->prepare($command);
    $stmt->execute([':query' => "%" . $query . "%"]);
    
    // Fetch the results as an associative array
    $ingredients = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return the results as JSON
    echo json_encode($ingredients);
}
else {
    echo json_encode([]);
}
?>
