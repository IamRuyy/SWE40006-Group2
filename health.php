<?php
// Example health check: Connect to database
require_once('settings.php');

$conn = @mysqli_connect($host, $user, $pswd, $dbnm);

if ($conn && mysqli_ping($conn)) {
    // Return a 200 OK status if everything is fine
    http_response_code(200);
    echo "OK";
} else {
    // Return a 500 Internal Server Error if there is an issue
    http_response_code(500);
    echo "Database connection failed";
}

// Close connection
if ($conn) {
    mysqli_close($conn);
}
?>