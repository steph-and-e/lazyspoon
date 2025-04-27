<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/script.js"></script>
</head>

<body>
    <div class="center">
        <div class="form-container">
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
                // Clear previous message classes
                echo '<script>
                document.querySelectorAll(".inputForm").forEach(el => {
                    el.classList.remove("success", "error");
                });
            </script>';

                // Validate inputs
                if (empty($email) || empty($username) || empty($password)) {
                    echo "<div class='message-container'><p class='error'>❌ Please fill in all fields.</p></div>";
                    echo '<script>
                    document.querySelectorAll(".inputForm").forEach(el => {
                        el.classList.add("error");
                    });
                </script>';
                    return false;
                }

                if (emailExists($email, $dbh)) {
                    echo "<div class='message-container'><p class='error'>❌ This email is already taken.</p></div>";
                    echo '<script>
                    document.querySelector("input[name=\"email\"]").closest(".inputForm").classList.add("error");
                </script>';
                    return false;
                }

                if (usernameExists($username, $dbh)) {
                    echo "<div class='message-container'><p class='error'>❌ This username is already taken.</p></div>";
                    echo '<script>
                    document.querySelector("input[name=\"username\"]").closest(".inputForm").classList.add("error");
                </script>';
                    return false;
                }

                if (strlen($password) < 8) {
                    echo "<div class='message-container'><p class='error'>❌ Password must be at least 8 characters.</p></div>";
                    echo '<script>
                    document.querySelector("input[name=\"password\"]").closest(".inputForm").classList.add("error");
                </script>';
                    return false;
                }

                // Hash the password
                $hash = password_hash($password, PASSWORD_DEFAULT);
                if ($hash === false) {
                    echo "<p class='error'>❌ Failed to secure your password. Please try again.</p>";
                    return false;
                }

                try {
                    // Insert new user
                    $stmt = $dbh->prepare("INSERT INTO users (username, email, password_hash, `role`) VALUES (?, ?, ?, ?)");
                    $success = $stmt->execute([$username, $email, $hash, "user"]);

                    if ($success) {
                        echo "<div class='message-container'><p class='success'>✅ Registration successful! Redirecting...</p></div>";
                        echo '<script>
                        document.querySelectorAll(".inputForm").forEach(el => {
                            el.classList.add("success");
                        });
                    </script>';
                        // Start session immediately after registration
                        session_start();
                        session_regenerate_id(true);

                        // Store user data in session
                        $_SESSION['user_id'] = $dbh->lastInsertId();
                        $_SESSION['username'] = $username;
                        $_SESSION['email'] = $email;
                        $_SESSION['role'] = 'user';
                        $_SESSION['logged_in'] = true;
                        $_SESSION['created_at'] = time();

                        echo "<p class='success'>✅ Registration successful! Redirecting to login...</p>";

                        // Redirect to member area
                        //header("Refresh: 2; url=dashboard.php");
                        header("Refresh: 2; url=login.php");
                        return true;
                    } else {
                        echo "<p class='error'>❌ There was an error with the registration. Please try again.</p>";
                        return false;
                    }
                } catch (PDOException $e) {
                    error_log("Registration error: " . $e->getMessage());
                    echo "<p class='error'>❌ A system error occurred. Please try again later.</p>";
                    return false;
                }
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
                $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
                $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

                if (!empty($username) && !empty($email) && !empty($password)) {
                    register($email, $username, $password, $dbh);
                } else {
                    echo "<p>❌ Please fill in all fields.</p>";
                }
            }
            ?>
            <form class="form" method="post" action="register.php">
                <div class="flex-column">
                    <label>Username </label>
                </div>
                <div class="inputForm">
                    <svg
                        height="60"
                        viewBox="0 -9 32 32"
                        width="40"
                        xmlns="http://www.w3.org/2000/svg">
                        <g id="Layer_3" data-name="Layer 3">
                            <path
                                d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1z"></path>
                        </g>
                    </svg>
                    <input type="text" class="input" placeholder="Enter your username" name="username" />
                </div>
                <div class="flex-column">
                    <label>Email </label>
                </div>
                <div class="inputForm">
                    <svg
                        height="20"
                        viewBox="0 0 32 32"
                        width="20"
                        xmlns="http://www.w3.org/2000/svg">
                        <g id="Layer_3" data-name="Layer 3">
                            <path
                                d="m30.853 13.87a15 15 0 0 0 -29.729 4.082 15.1 15.1 0 0 0 12.876 12.918 15.6 15.6 0 0 0 2.016.13 14.85 14.85 0 0 0 7.715-2.145 1 1 0 1 0 -1.031-1.711 13.007 13.007 0 1 1 5.458-6.529 2.149 2.149 0 0 1 -4.158-.759v-10.856a1 1 0 0 0 -2 0v1.726a8 8 0 1 0 .2 10.325 4.135 4.135 0 0 0 7.83.274 15.2 15.2 0 0 0 .823-7.455zm-14.853 8.13a6 6 0 1 1 6-6 6.006 6.006 0 0 1 -6 6z"></path>
                        </g>
                    </svg>
                    <input type="text" class="input" placeholder="Enter your Email" name="email" />
                </div>

                <div class="flex-column">
                    <label>Password </label>
                </div>
                <div class="inputForm">
                    <svg
                        height="20"
                        viewBox="-64 0 512 512"
                        width="20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="m336 512h-288c-26.453125 0-48-21.523438-48-48v-224c0-26.476562 21.546875-48 48-48h288c26.453125 0 48 21.523438 48 48v224c0 26.476562-21.546875 48-48 48zm-288-288c-8.8125 0-16 7.167969-16 16v224c0 8.832031 7.1875 16 16 16h288c8.8125 0 16-7.167969 16-16v-224c0-8.832031-7.1875-16-16-16zm0 0"></path>
                        <path d="m304 224c-8.832031 0-16-7.167969-16-16v-80c0-52.929688-43.070312-96-96-96s-96 43.070312-96 96v80c0 8.832031-7.167969 16-16 16s-16-7.167969-16-16v-80c0-70.59375 57.40625-128 128-128s128 57.40625 128 128v80c0 8.832031-7.167969 16-16 16zm0 0"></path>
                    </svg>

                    <input id="password" type="password" class="input" placeholder="Enter your Password" name="password" />

                    <span class="toggle-password">
                        <svg class="eye-icon eye-open" viewBox="0 0 24 24" width="20" height="20">
                            <path d="M12 9a3 3 0 0 1 3 3 3 3 0 0 1-3 3 3 3 0 0 1-3-3 3 3 0 0 1 3-3m0-4.5c5 0 9.27 3.11 11 7.5-1.73 4.39-6 7.5-11 7.5S2.73 16.39 1 12c1.73-4.39 6-7.5 11-7.5z" />
                        </svg>
                        <svg class="eye-icon eye-closed" viewBox="0 0 24 24" width="20" height="20" style="display:none;">
                            <path d="M11.83 9L15 12.16V12a3 3 0 0 0-3-3h-.17m-4.3.8l1.55 1.55c-.05.21-.08.42-.08.65a3 3 0 0 0 3 3c.22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53a5 5 0 0 1-5-5c0-.79.2-1.53.53-2.2M2 4.27l2.28 2.28.45.45C3.08 8.3 1.78 10 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.43.42L19.73 22 21 20.73 3.27 3M12 7a5 5 0 0 1 5 5c0 .64-.13 1.26-.36 1.82l2.93 2.93c1.5-1.25 2.7-2.89 3.43-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-4 .7l2.17 2.15C10.74 7.13 11.35 7 12 7z" />
                        </svg>
                    </span>
                </div>

                <button class="button-submit">Sign Up</button>
                <p class="p">Already have a account? <span class="span"><a href="login.php">login</a></span></p>
                <div class="flex-row">
                    <button class="btn google">
                        <svg
                            version="1.1"
                            width="20"
                            id="Layer_1"
                            xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink"
                            x="0px"
                            y="0px"
                            viewBox="0 0 512 512"
                            style="enable-background:new 0 0 512 512;"
                            xml:space="preserve">
                            <path
                                style="fill:#FBBB00;"
                                d="M113.47,309.408L95.648,375.94l-65.139,1.378C11.042,341.211,0,299.9,0,256
        c0-42.451,10.324-82.483,28.624-117.732h0.014l57.992,10.632l25.404,57.644c-5.317,15.501-8.215,32.141-8.215,49.456
        C103.821,274.792,107.225,292.797,113.47,309.408z"></path>
                            <path
                                style="fill:#518EF8;"
                                d="M507.527,208.176C510.467,223.662,512,239.655,512,256c0,18.328-1.927,36.206-5.598,53.451
        c-12.462,58.683-45.025,109.925-90.134,146.187l-0.014-0.014l-73.044-3.727l-10.338-64.535
        c29.932-17.554,53.324-45.025,65.646-77.911h-136.89V208.176h138.887L507.527,208.176L507.527,208.176z"></path>
                            <path
                                style="fill:#28B446;"
                                d="M416.253,455.624l0.014,0.014C372.396,490.901,316.666,512,256,512
        c-97.491,0-182.252-54.491-225.491-134.681l82.961-67.91c21.619,57.698,77.278,98.771,142.53,98.771
        c28.047,0,54.323-7.582,76.87-20.818L416.253,455.624z"></path>
                            <path
                                style="fill:#F14336;"
                                d="M419.404,58.936l-82.933,67.896c-23.335-14.586-50.919-23.012-80.471-23.012
        c-66.729,0-123.429,42.957-143.965,102.724l-83.397-68.276h-0.014C71.23,56.123,157.06,0,256,0
        C318.115,0,375.068,22.126,419.404,58.936z"></path>
                        </svg>

                        Google
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>