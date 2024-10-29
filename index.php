<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
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
<div class="container">
    <p><b>Name: </b>Nguyen Pham Duy</p>
    <p><b>Student ID: </b>104058375</p>
    <p><b>Email: </b><a href="mailto:104058375@student.swin.edu.au">104058375@student.swin.edu.au</a></p>
    <p>I declare that this assignment is my individual work. I have not worked collaboratively nor
    have I copied from any other student’s work or from any other source.</p>

<?php
require_once("settings.php");

$conn = @mysqli_connect($host, $user, $pswd)
    or die('Failed to connect to server');

@mysqli_select_db($conn, $dbnm)
    or die('Database not available');

// Create friends table
$friends_table = "CREATE TABLE IF NOT EXISTS friends (
    friend_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    friend_email VARCHAR(50) NOT NULL,
    password VARCHAR(20) NOT NULL,
    profile_name VARCHAR(30) NOT NULL,
    date_started DATE NOT NULL,
    num_of_friends INT UNSIGNED
)";

if (mysqli_query($conn, $friends_table)) {
    echo "<p style=color:green>Table 'friends' created successfully.</p>";
} else {
    echo "<p style=color:red>Error creating table: " . mysqli_error($conn) . "</p>";
}

// Create myfriends table
$myfriends_table = "CREATE TABLE IF NOT EXISTS myfriends (
    friend_id1 INT NOT NULL,
    friend_id2 INT NOT NULL,
    CONSTRAINT fk_friend1 FOREIGN KEY (friend_id1) REFERENCES friends(friend_id),
    CONSTRAINT fk_friend2 FOREIGN KEY (friend_id2) REFERENCES friends(friend_id),
    CONSTRAINT pk_friends PRIMARY KEY (friend_id1, friend_id2)
)";

if (mysqli_query($conn, $myfriends_table)) {
    echo "<p style=color:green>Table 'myfriends' created successfully.</p>";
} else {
    echo "<p style=color:red>Error creating table: " . mysqli_error($conn) . "</p>";
}

$select_friends = "SELECT * FROM friends";
$friends_row = mysqli_query($conn, $select_friends);

// Insert sample data into friends table
if (mysqli_num_rows($friends_row) > 0) {
    echo "<p style=color:blue>Table 'friends' already has data.</p>";
} else {
    $sample_friends = "INSERT INTO friends (friend_email, password, profile_name, date_started, num_of_friends)
    VALUES
    ('user1@gmail.com', 'password1', 'John Doe', '2024-01-01', 0),
    ('user2@gmail.com', 'password2', 'Jane Smith', '2024-02-01', 0),
    ('user3@gmail.com', 'password3', 'Alice Johnson', '2024-03-01', 0),
    ('user4@gmail.com', 'password4', 'Bob Williams', '2024-04-01', 0),
    ('user5@gmail.com', 'password5', 'Eva Brown', '2024-05-01', 0),
    ('user6@gmail.com', 'password6', 'Mike Davis', '2024-06-01', 0),
    ('user7@gmail.com', 'password7', 'Sara Lee', '2024-07-01', 0),
    ('user8@gmail.com', 'password8', 'Chris Wilson', '2024-08-01', 0),
    ('user9@gmail.com', 'password9', 'Lily Martinez', '2024-09-01', 0),
    ('user10@gmail.com', 'password10', 'Tom Adams', '2024-10-01', 0)";

    if (mysqli_query($conn, $sample_friends)) {
        echo "<p style=color:green>Sample data inserted into 'friends' table successfully.</p>";
    } else {
        echo "<p style=color:red>Error inserting sample data: " . mysqli_error($conn) . "</p>";
    }
}

$select_myfriends = "SELECT * FROM myfriends";
$myfriends_row = mysqli_query($conn, $select_myfriends);

// Insert sample data into myfriends table
if (mysqli_num_rows($myfriends_row) > 0) {
    echo "<p style=color:blue>Table 'myfriends' already has data.</p>";
} else {
    $sample_myfriends = "INSERT INTO myfriends (friend_id1, friend_id2)
    VALUES
    (1, 2),
    (1, 3),
    (1, 4),
    (1, 5),
    (1, 6),
    (2, 3),
    (2, 4),
    (2, 5),
    (2, 6),
    (2, 7),
    (3, 4),
    (3, 5),
    (3, 6),
    (3, 7),
    (3, 8),
    (4, 5),
    (4, 6),
    (4, 7),
    (4, 8),
    (4, 9),
    (5, 6)";

    if (mysqli_query($conn, $sample_myfriends)) {
        echo "<p style=color:green>Sample data inserted into 'myfriends' table successfully.</p>";
    } else {
        echo "<p style=color:red>Error inserting sample data: " . mysqli_error($conn) . "</p>";
    }
}

mysqli_close($conn);
?>
</div>
<footer class="footer">
    <p>&copy; 2024 Nguyen Pham Duy</p>
</footer>
</body>
</html>
