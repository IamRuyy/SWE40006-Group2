<?php
    session_start();

    require_once("settings.php");

    $conn = @mysqli_connect($host, $user, $pswd)
        or die('Failed to connect to server');

    @mysqli_select_db($conn, $dbnm)
        or die('Database not available');

    function sanitizeInput($input)
    {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
    }

    $email = "";
    $emailErr = $passwordErr = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validate email
        if (empty($_POST["email"])) {
            $emailErr = "Email is required";
        } else {
            $email = sanitizeInput($_POST["email"]);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "Invalid email format";
            }
        }

        // Validate password
        if (empty($_POST["password"])) {
            $passwordErr = "Password is required";
        } else {
            $password = sanitizeInput($_POST["password"]);
        }

        if (!empty($email) && !empty($password)) {
            $check_email = "SELECT * FROM friends WHERE friend_email = '$email'";
            $result = mysqli_query($conn, $check_email);

            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                if ($password == $row["password"]) {
                    // Set the user's email in the session
                    $_SESSION["user_email"] = $email;
                    $_SESSION["login_status"] = true;
                    header("Location: friendlist.php");
                    exit();
                } else {
                    $passwordErr = "Incorrect password";
                }
            } else {
                $emailErr = "Email not found";
            }
        }
    }
    mysqli_close($conn);           
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log-In</title>
    <link rel="stylesheet" href="./style/style.css">
</head>
<body>
<div class="header">
    <a href="index.php" class="logo">My Friends System</a>
        <div class="nav">
            <a href="index.php">Home</a>
            <a href="signup.php">Sign-Up</a>
            <a href="login.php">Log-In</a>
            <a href="about.php">About</a>
        </div>
    </div>
    <div class='container'>
    <h2>Sign Up</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="email">Email:</label>
        <input type="text" id="email" name="email" value="<?php echo $email; ?>">
        <span style="color: red;"><?php echo $emailErr; ?></span><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password">
        <span style="color: red;"><?php echo $passwordErr; ?></span><br><br>

        <input type="submit" value="Log In">
        <input type="reset" value="Reset">
    </form>
    <br>
    <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
    <a href="index.php">Back to Home</a>
</div>

<footer class="footer">
    <p>&copy; 2024 Nguyen Pham Duy</p>
</footer>
</body>
</html>
