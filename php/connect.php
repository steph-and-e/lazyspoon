<?php

    try {
        $dbh = new PDO(
            "mysql:host=localhost;dbname=faghanim_db",
            "faghanim",
            "13821382Mo@"
        );
    }
    catch (Exception $e) {
        die("ERROR: Couldn't connect. {$e->getMessage()}");
    }
    // echo "<p>Connected</p>"; // Success message