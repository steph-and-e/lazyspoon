<?php
/**
 * Author: Mostafa
 * Student Number: 400599915
 * Date Created: 2025/03/29
 * Description: Establishes a connection to the MySQL database.
 */
try {
    $dbh = new PDO(
        "mysql:host=localhost;dbname=faghanim_db",
        "faghanim_local",
        "JUCaon3+"
    );
} catch (Exception $e) {
    die("ERROR: Couldn't connect. {$e->getMessage()}");
}
