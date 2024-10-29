<?php
    session_start();

    // Check if user is not logged in, redirect to login page
    if (!isset($_SESSION["login_status"]) || $_SESSION["login_status"] !== true) {
        header("Location: login.php");
        exit();
    }

    require_once("settings.php");

    $conn = @mysqli_connect($host, $user, $pswd)
        or die('Failed to connect to server');

    @mysqli_select_db($conn, $dbnm)
        or die('Database not available');

    if (isset($_SESSION["user_email"])) {
        $loggedInUserEmail = $_SESSION["user_email"];

        // Get logged-in user's profile name
        $getProfileName = "SELECT profile_name FROM friends WHERE friend_email = '$loggedInUserEmail'";
        $profileResult = mysqli_query($conn, $getProfileName);
        $profileRow = mysqli_fetch_assoc($profileResult);
        $loggedInUserProfileName = $profileRow['profile_name'];

        // Get logged-in user's friends' profile names and count
        $getFriends = "SELECT f.friend_id, f.profile_name
                            FROM friends f
                            JOIN myfriends mf ON (f.friend_id = mf.friend_id1 OR f.friend_id = mf.friend_id2)
                            WHERE mf.friend_id1 = (SELECT friend_id FROM friends WHERE friend_email = '$loggedInUserEmail')
                            OR mf.friend_id2 = (SELECT friend_id FROM friends WHERE friend_email = '$loggedInUserEmail')";
        $friendsResult = mysqli_query($conn, $getFriends);
        $numFriends = "SELECT COUNT(*) as total_friends FROM myfriends WHERE friend_id1 IN (SELECT friend_id FROM friends WHERE friend_email = '$loggedInUserEmail')
                            OR friend_id2 IN (SELECT friend_id FROM friends WHERE friend_email = '$loggedInUserEmail')";
        $numFriendsResult = mysqli_query($conn, $numFriends);
        $numFriendsRow = mysqli_fetch_assoc($numFriendsResult);
        $totalFriends = $numFriendsRow['total_friends'];

        // Handle unfriending
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['unfriend'])) {
            $unfriendId = $_POST['unfriend'];
            $unfriendProcess = "DELETE FROM myfriends WHERE (friend_id1 = '$unfriendId' AND friend_id2 IN (SELECT friend_id FROM friends WHERE friend_email = '$loggedInUserEmail'))
                            OR (friend_id2 = '$unfriendId' AND friend_id1 IN (SELECT friend_id FROM friends WHERE friend_email = '$loggedInUserEmail'))";
            mysqli_query($conn, $unfriendProcess);

            // Number of friends for the logged-in user
            $updatenumFriends = "UPDATE friends SET num_of_friends = (SELECT COUNT(*) FROM myfriends WHERE (friend_id1 = friend_id OR friend_id2 = friend_id)) WHERE friend_email = '$loggedInUserEmail'";
            mysqli_query($conn, $updatenumFriends);

            // Refresh friends list after unfriending
            header("Location: friendlist.php");
            exit();
        }
    } else {
        // If session user_email is not set, redirect to login
        header("Location: login.php");
        exit();
    }

    mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Friend List</title>
    <link rel="stylesheet" href="./style/style.css">
    <style>
        table {
            border-collapse: collapse;
            width: 50%;
            margin: 20px auto;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .center {
            text-align: center;
        }
    </style>
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
    <h2>Welcome, <?php echo $loggedInUserProfileName; ?>!</h2>
    <h3>My Friends List</h3>

    <p>Total number of friends is: <?php echo $totalFriends; ?></p>

    <table>
        <tr>
            <th>Profile Name</th>
            <th>Action</th>
        </tr>
        <?php
            while ($row = mysqli_fetch_assoc($friendsResult)) {
                if ($row['profile_name'] !== $loggedInUserProfileName) {
                    echo "<tr>";
                    echo "<td>".$row['profile_name']."</td>";
                    echo "<td class='center'><form method='post'><input type='hidden' name='unfriend' value='".$row['friend_id']."'><input type='submit' value='Unfriend'></form></td>";
                    echo "</tr>";
                }
            }
        ?>
    </table>

    <div class="center">
        <a href="friendadd.php">Add More Friends</a> | <a href="logout.php">Log out</a>
    </div>
        </div>
<footer class="footer">
    <p>&copy; 2024 Nguyen Pham Duy</p>
</footer>
</body>
</html>
