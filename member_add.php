<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
    require_once("settings.php");

        //Connect to mysql server
    $conn = @mysqli_connect($host, $user, $pswd)
        or die('Failed to connect to server');
    //Use database
    @mysqli_select_db($conn, $dbnm)
        or die('Database not available');

    // SQL to create table
    $createTable = "CREATE TABLE IF NOT EXISTS vipmembers (
        member_id INT AUTO_INCREMENT PRIMARY KEY,
        fname VARCHAR(40),
        lname VARCHAR(40),
        gender VARCHAR(1),
        email VARCHAR(40),
        phone VARCHAR(20)
    )";

    @mysqli_query($conn, $createTable)
        or die('Error creating table' );

    // Insert data into table
    if (isset($_POST['fname']) && isset($_POST['lname']) && isset($_POST['gender']) && isset($_POST['email']) && isset($_POST['phone'])
    && !empty($_POST['fname']) && !empty($_POST['lname']) && !empty($_POST['gender']) && !empty($_POST['email']) && !empty($_POST['phone'])) {
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $gender = $_POST['gender'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        $insert = "INSERT INTO vipmembers (fname, lname, gender, email, phone)
        VALUES ('$fname', '$lname', '$gender', '$email', '$phone')";

        echo "<h1>Successfully adding new member</h1>";
        echo "<p><b>First Name:</b> $fname</p>";
        echo "<p><b>Last Name:</b> $lname</p>";
        echo "<p><b>Gender:</b> $gender</p>";
        echo "<p><b>Email:</b> $email</p>";
        echo "<p><b>Phone:</b> $phone</p>";
        
        @mysqli_query($conn, $insert) 
            or die('Error inserting data');
    }
    else {
        echo "<p style='color:red'>Please enter all the inputs in the form.</p>";
    }

    //Close the connection
    mysqli_close($conn);
?>
<a href="vip_member.php">Return to Home Page</a><br>
<a href="member_add_form.php">Add a new member</a>
</body>
</html>