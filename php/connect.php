<?php

    try {
        $dbh = new PDO(
            "mysql:host=localhost;dbname=faghanim_db",
            "root",
            ""
        );
    }
    catch (Exception $e) {
        die("ERROR: Couldn't connect. {$e->getMessage()}");
    }
    // echo "<p>Connected</p>"; // Success message