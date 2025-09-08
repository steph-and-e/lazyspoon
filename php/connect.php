<?php
try {
    $dbh = new PDO(
        "mysql:host=localhost;dbname=your_database_name;charset=utf8", // DSN
        "your_username", // Database username
        "your_password"  // Database password
    );

    // Set PDO error mode to exception
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected successfully!";
} catch (PDOException $e) {
    die("ERROR: Couldn't connect. " . $e->getMessage());
}
?>
