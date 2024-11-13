<?php
require_once __DIR__ . '/../settings.php';

class DatabaseTest extends PHPUnit\Framework\TestCase {
    
    protected $conn;

    protected function setUp(): void {
        $this->conn = @mysqli_connect($GLOBALS['host'], $GLOBALS['user'], $GLOBALS['pswd']);
        $this->assertNotFalse($this->conn, "Test failed: Failed to connect to MySQL server.");

        $selected = @mysqli_select_db($this->conn, $GLOBALS['dbnm']);
        $this->assertNotFalse($selected, "Test failed: Database not available.");
    }

    protected function tearDown(): void {
        if ($this->conn) {
            mysqli_close($this->conn);
        }
    }

    public function testTableCreation() {
        $createTable = "CREATE TABLE IF NOT EXISTS vipmembers (
            member_id INT AUTO_INCREMENT PRIMARY KEY,
            fname VARCHAR(40),
            lname VARCHAR(40),
            gender VARCHAR(1),
            email VARCHAR(40),
            phone VARCHAR(20)
        )";

        $result = mysqli_query($this->conn, $createTable);
        $this->assertNotFalse($result, "Test 1 failed: Error creating table.");
    }

    public function testInsertRecord() {
        $fname = "Test";
        $lname = "User";
        $gender = "M";
        $email = "testuser@example.com";
        $phone = "1234567890";

        $insert = "INSERT INTO vipmembers (fname, lname, gender, email, pho)
                   VALUES ('$fname', '$lname', '$gender', '$email', '$phone')";

        $result = mysqli_query($this->conn, $insert);
        $this->assertNotFalse($result, "Test 2 failed: Error inserting data.");
    }

    public function testVerifyRecord() {
        $query = "SELECT * FROM vipmembers WHERE fname='Test' AND lname='User' AND email='testuser@example.com'";
        $result = mysqli_query($this->conn, $query);
        
        $this->assertNotFalse($result, "Test 3 failed: Query execution failed.");
        $this->assertGreaterThan(0, mysqli_num_rows($result), "Test 3 failed: Record not found in database.");
    }

    public function testCleanupRecord() {
        $deleteQuery = "DELETE FROM vipmembers WHERE fname='Test' AND lname='User' AND email='testuser@example.com'";
        $result = mysqli_query($this->conn, $deleteQuery);
        
        $this->assertNotFalse($result, "Test 4 failed: Could not delete test record.");
    }
}
