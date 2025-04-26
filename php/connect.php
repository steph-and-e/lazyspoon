<?php

    try {
        $dbh = new PDO(
            // "mysql:host=localhost;dbname=faghanim_db",
            // "faghanim",
            // "13821382Mo@"
            "mysql:host=localhost;dbname=lazy_spoon",
            "root",
            ""
            // "mysql:host=localhost;dbname=li3424_db",
            // "li3424_local",
            // "uDqHFzSw"

        );
    }
    catch (Exception $e) {
        die("ERROR: Couldn't connect. {$e->getMessage()}");
    }
    // echo "<p>Connected</p>"; // Success message