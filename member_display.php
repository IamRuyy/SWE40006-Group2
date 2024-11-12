<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="description" content="Web application development" />
    <meta name="keywords" content="PHP" />
    <meta name="author" content="Nguyen Pham Duy" />
    <link rel="stylesheet" href="styles/styles.css">
<title>PHP Web Application</title>
</head>
<body>
<h1>Member Listing</h1>
<?php
    require_once ("settings.php");
    //Connect to mysql server
    $conn = @mysqli_connect($host, $user, $pswd)
        or die('Failed to connect to server');
    //Use database
    @mysqli_select_db($conn, $dbnm)
        or die('Database not available');

    //Get data from database
    $query = "SELECT * FROM vipmembers";
    $results = mysqli_query($conn, $query);

    //Display data
    echo "<table width='100%' border='1'>";
    echo "<tr><th>Member ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            </tr>";
            $row = mysqli_fetch_row($results);
            while ($row) {
            echo "<tr><td>{$row[0]}</td>";
            echo "<td>{$row[1]}</td>";
            echo "<td>{$row[2]}</td></tr>";
            $row = mysqli_fetch_row($results);
            }
    echo "</table>";

    //Close the connection
    mysqli_free_result($results);
    mysqli_close($conn);
?>
<a href="vip_member.php">Return to Home Page</a>
</body>
</html>