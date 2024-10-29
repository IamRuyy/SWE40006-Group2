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

$email = $profile_name = "";
$emailErr = $profileNameErr = $passwordErr = $confirmPasswordErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate email
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = sanitizeInput($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        } else {
            $check_mail = "SELECT * FROM friends WHERE friend_email = '$email'";
            $result = mysqli_query($conn, $check_mail);
            if (mysqli_num_rows($result) > 0) {
                $emailErr = "Email already exists";
            }
        }
    }

    // Validate profile name
    if (empty($_POST["profile_name"])) {
        $profileNameErr = "Profile name is required";
    } else {
        $profile_name = sanitizeInput($_POST["profile_name"]);
        if (!preg_match("/^[a-zA-Z ]*$/", $profile_name)) {
            $profileNameErr = "Only letters and white space allowed";
        }
    }

    // Validate password
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = sanitizeInput($_POST["password"]);
        if (!preg_match("/^[a-zA-Z0-9]*$/", $password)) {
            $passwordErr = "Only letters and numbers allowed";
        }
    }

    // Validate confirm password
    if (empty($_POST["confirm_password"])) {
        $confirmPasswordErr = "Confirm password is required";
    } else {
        $confirm_password = sanitizeInput($_POST["confirm_password"]);
        if ($confirm_password !== $password) {
            $confirmPasswordErr = "Passwords do not match";
        }
    }

    // Insert into database
    if (empty($emailErr) && empty($profileNameErr) && empty($passwordErr) && empty($confirmPasswordErr)) {
        $date_started = date("Y-m-d");
        $insert_data = "INSERT INTO friends (friend_email, password, profile_name, date_started, num_of_friends)
                        VALUES ('$email', '$password', '$profile_name', '$date_started', 0)";
        if (mysqli_query($conn, $insert_data)) {
            // Set login status and user_email in session
            $_SESSION["login_status"] = true;
            $_SESSION["user_email"] = $email;
            
            // Redirect to friendadd.php after successful sign-up
            header("Location: friendadd.php");
            exit();
        } else {
            echo "Error: " . $insert_data . "<br>" . mysqli_error($conn);
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
    <title>Sign-Up</title>
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
        <div>
            <label for="email">Email</label>
            <input type="text" name="email" value="<?php echo $email; ?>">
            <span style="color: red;"><?php echo $emailErr; ?></span>
        </div>
        <div>
            <label for="profile_name">Profile Name</label>
            <input type="text" name="profile_name" value="<?php echo $profile_name; ?>">
            <span style="color: red;"><?php echo $profileNameErr; ?></span>
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" name="password">
            <span style="color: red;"><?php echo $passwordErr; ?></span>
        </div>
        <div>
            <label for="confirm_password">Confirm Password</label>
            <input type="password" name="confirm_password">
            <span style="color: red;"><?php echo $confirmPasswordErr; ?></span>
        </div>
        <div>
            <input type="submit" value="Sign Up">
            <input type="reset" value="Reset">
        </div>
    </form>
    <br>
    <p>Already have an account? <a href="login.php">Log in</a></p>
    <a href="index.php">Back to Home</a>
    </div>
<footer class="footer">
    <p>&copy; 2024 Nguyen Pham Duy</p>
</footer>
</body>
</html>
