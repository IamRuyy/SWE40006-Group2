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

        // Pagination
        $results_per_page = 5;

        // Determine current page number
        if (!isset($_GET['page'])) {
            $page = 1;
        } else {
            $page = $_GET['page'];
        }

        // Calculate the starting point
        $start_from = ($page - 1) * $results_per_page;

        // Retrieve all registered users except friends of the logged-in user with pagination
        $getRegisteredUsers = "SELECT friend_id, profile_name
                                    FROM friends
                                    WHERE friend_email NOT IN (
                                        SELECT friend_email
                                        FROM friends f
                                        JOIN myfriends mf ON (f.friend_id = mf.friend_id1 OR f.friend_id = mf.friend_id2)
                                        WHERE mf.friend_id1 = (SELECT friend_id FROM friends WHERE friend_email = '$loggedInUserEmail')
                                        OR mf.friend_id2 = (SELECT friend_id FROM friends WHERE friend_email = '$loggedInUserEmail')
                                        )
                                    AND friend_email != '$loggedInUserEmail'
                                    ORDER BY profile_name
                                    LIMIT $start_from, $results_per_page";
        $registeredUsersResult = mysqli_query($conn, $getRegisteredUsers);

        // Count total number of available friends
        $countTotalFriends = "SELECT COUNT(*) as total_friends
                                    FROM friends
                                    WHERE friend_email NOT IN (
                                        SELECT friend_email
                                        FROM friends f
                                        JOIN myfriends mf ON (f.friend_id = mf.friend_id1 OR f.friend_id = mf.friend_id2)
                                        WHERE mf.friend_id1 = (SELECT friend_id FROM friends WHERE friend_email = '$loggedInUserEmail')
                                        OR mf.friend_id2 = (SELECT friend_id FROM friends WHERE friend_email = '$loggedInUserEmail')
                                        )
                                    AND friend_email != '$loggedInUserEmail'";
        $totalFriendsResult = mysqli_query($conn, $countTotalFriends);
        $totalFriendsRow = mysqli_fetch_assoc($totalFriendsResult);
        $totalFriendsAvailable = $totalFriendsRow['total_friends'];

        // Handle adding friends
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addFriend'])) {
            $friendId = $_POST['addFriend'];
            $addFriend = "INSERT INTO myfriends (friend_id1, friend_id2) VALUES (
                                    (SELECT friend_id FROM friends WHERE friend_email = '$loggedInUserEmail'),
                                    '$friendId'
                                )";
            mysqli_query($conn, $addFriend);

            // Update the number of friends for the logged-in user
            $updateNumFriends = "UPDATE friends SET num_of_friends = (SELECT COUNT(*) FROM myfriends WHERE (friend_id1 = friend_id OR friend_id2 = friend_id)) WHERE friend_email = '$loggedInUserEmail'";
            mysqli_query($conn, $updateNumFriends);

            header("Location: friendadd.php?page=$page");
            exit();
        }
    } else {
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
    <title>Add Friend List</title>
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
    <h2>Add some new friends for you, <?php echo $loggedInUserProfileName; ?>!</h3>
    <h3>Add Friends</h3>
    <p>There are total <?php echo $totalFriendsAvailable; ?> number of friends available.</p>

    <table>
        <tr>
            <th>Profile Name</th>
            <th>Action</th>
        </tr>
        <?php
            while ($row = mysqli_fetch_assoc($registeredUsersResult)) {
                echo "<tr>";
                echo "<td>".$row['profile_name']."</td>";
                echo "<td class='center'><form method='post'><input type='hidden' name='addFriend' value='".$row['friend_id']."'><input type='submit' value='Add Friend'></form></td>";
                echo "</tr>";
            }
        ?>
    </table>

    <div class="center pagination">
        <?php
            $total_pages = ceil($totalFriendsAvailable / $results_per_page);
            
            if ($page > 1) {
                echo "<a href='friendadd.php?page=".($page - 1)."'>Previous</a>";
            }
            
            for ($i = 1; $i <= $total_pages; $i++) {
                if ($i == $page) {
                    echo "<a class='active' href='friendadd.php?page=".$i."'>".$i."</a>";
                } else {
                    echo "<a href='friendadd.php?page=".$i."'>".$i."</a>";
                }
            }
            
            if ($page < $total_pages) {
                echo "<a href='friendadd.php?page=".($page + 1)."'>Next</a>";
            }
        ?>
    </div>
    <br>
    <div class="center">
        <a href="friendlist.php">Friend List</a> | <a href="logout.php">Log out</a>
    </div>
    </div>

<footer class="footer">
    <p>&copy; 2024 Nguyen Pham Duy</p>
</footer>
</body>
</html>
