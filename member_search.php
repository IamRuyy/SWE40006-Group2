<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>PHP Web Application</title>
</head>
<body>
<h1>Search Members</h1>
    <form action="" method="GET">
        <label for="lname">Search by Last Name:</label>
        <input type="text" id="lname" name="lname">
        <input type="submit" value="Search">
        <input type="reset" value="Reset">
    </form>
<?php
    require_once ("settings.php");
    //Connect to mysql server
    $conn = @mysqli_connect($host, $user, $pswd)
        or die('Failed to connect to server');
    //Use database
    @mysqli_select_db($conn, $dbnm)
        or die('Database not available');

    if(isset($_GET['lname']) && !empty($_GET['lname'])) {
        $lname = $_GET['lname'];
        $query = "SELECT member_id, fname, lname, email FROM vipmembers WHERE lname = '$lname'";
        $results = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($results) > 0) {
        echo "<table width='100%' border='1'>";
        echo "<tr><th>Member ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            </tr>";
            $row = mysqli_fetch_row($results);
            while ($row) {
                echo "<tr><td>{$row[0]}</td>";
                echo "<td>{$row[1]}</td>";
                echo "<td>{$row[2]}</td>";
                echo "<td>{$row[3]}</td></tr>";
            $row = mysqli_fetch_row($results);
            }
        echo "</table>";
        }
        else {
            echo "<p style='color:red; text-align:center;'>The Last Name '$lname' is not found</p>";
        }

        //Close the connection
        mysqli_free_result($results);
        mysqli_close($conn);
    }
    else {
        echo "<p style='color:red; text-align:center;'>Please enter the input for Last Name</p>";
    }
?>
<a href="vip_member.php">Return to Home Page</a>
</body>
</html>