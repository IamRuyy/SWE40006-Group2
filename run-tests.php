<?php
require_once("settings.php");

// Connect to MySQL server
$conn = @mysqli_connect($host, $user, $pswd)
    or die("Test failed: Failed to connect to MySQL server.");

// Select the database
@mysqli_select_db($conn, $dbnm)
    or die("Test failed: Database not available.");

// Test 1: Check Table Creation
$createTable = "CREATE TABLE IF NOT EXISTS vipmembers (
    member_id INT AUTO_INCREMENT PRIMARY KEY,
    fname VARCHAR(40),
    lname VARCHAR(40),
    gender VARCHAR(1),
    email VARCHAR(40),
    phone VARCHAR(20)
)";

if (@mysqli_query($conn, $createTable)) {
    echo "Test 1 passed: Table created successfully or already exists.<br>";
} else {
    die("Test 1 failed: Error creating table.");
}

// Test 2: Insert a Test Record
$fname = "Test";
$lname = "User";
$gender = "M";
$email = "testuser@example.com";
$phone = "1234567890";

$insert = "INSERT INTO vipmembers (fname, lname, gender, email, phone)
VALUES ('$fname', '$lname', '$gender', '$email', '$phone')";

if (@mysqli_query($conn, $insert)) {
    echo "Test 2 passed: Record inserted successfully.<br>";
} else {
    die("Test 2 failed: Error inserting data.");
}

// Test 3: Verify the Test Record in Database
$query = "SELECT * FROM vipmembers WHERE fname='$fname' AND lname='$lname' AND email='$email'";
$result = @mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    echo "Test 3 passed: Record exists in database.<br>";
} else {
    die("Test 3 failed: Record not found in database.");
}

// Test 4: Clean up Test Data
$deleteQuery = "DELETE FROM vipmembers WHERE fname='$fname' AND lname='$lname' AND email='$email'";
if (@mysqli_query($conn, $deleteQuery)) {
    echo "Test 4 passed: Test record cleaned up successfully.<br>";
} else {
    echo "Test 4 failed: Could not delete test record.";
}

// Close the connection
mysqli_close($conn);
?>