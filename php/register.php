<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
</head>

<body>
    <?php
    include "connect.php";

    function usernameExists($username, $dbh)
    {
        $command = "SELECT COUNT(*) FROM users WHERE username = ?";
        $stmt = $dbh->prepare($command);
        $stmt->execute([$username]);
        return $stmt->fetchColumn() > 0;
    }
    function emailExists($email, $dbh)
    {
        $command = "SELECT COUNT(*) FROM users WHERE email = ?";
        $stmt = $dbh->prepare($command);
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }
    function register($email, $username, $password, $dbh)
    {
        if (emailExists($email, $dbh)) {
            echo "<p>This email is already taken. Please choose a different one.</p>";
            return;
        }

        if (usernameExists($username, $dbh)) {
            echo "<p>This username is already taken. Please choose a different one.</p>";
            return;
        }
        // hashing the password
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $command = "INSERT INTO users (username, email, password_hash, `role`) VALUES (?,?,?,?)";
        $stmt = $dbh->prepare($command);
        $register = $stmt->execute([$username, $email, $hash, "user"]);
        if ($register) {
            echo "<p>✅ Registration successful! You can now log in.</p>";
        } else{
            echo "<p>❌ There was an error with the registration. Please try again.</p>";
        }
    }

    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

    register($email, $username, $hash, $dbh);

    ?>
</body>

</html>