<?php

    try {
        $dbh = new PDO(
            "mysql:host=localhost;dbname=lazy_spoon",
            "root",
            ""
        );
    }
    catch (Exception $e) {
        die("ERROR: Couldn't connect. {$e->getMessage()}");
    }
    // echo "<p>Connected</p>"; // Success message