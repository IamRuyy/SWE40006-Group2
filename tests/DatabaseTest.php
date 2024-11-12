<?php
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    private $conn;

    protected function setUp(): void
    {
        require_once("settings.php");
        $this->conn = mysqli_connect($host, $user, $pswd, $dbnm);
        $this->assertNotFalse($this->conn, "Failed to connect to MySQL server.");
    }

    protected function tearDown(): void
    {
        if ($this->conn) {
            mysqli_close($this->conn);
        }
    }

    public function testCreateTable()
    {
        $createTable = "CREATE TABLE IF NOT EXISTS vipmembers (
            member_id INT AUTO_INCREMENT PRIMARY KEY,
            fname VARCHAR(40),
            lname VARCHAR(40),
            gender VARCHAR(1),
            email VARCHAR(40),
            phone VARCHAR(20)
        )";

        $result = mysqli_query($this->conn, $createTable);
        $this->assertTrue($result, "Failed to create table.");
    }

    public function testInsertRecord()
    {
        $fname = "Test";
        $lname = "User";
        $gender = "M";
        $email = "testuser@example.com";
        $phone = "1234567890";

        $insert = "INSERT INTO vipmembers (fname, lname, gender, email, phone)
                   VALUES ('$fname', '$lname', '$gender', '$email', '$phone')";
        $result = mysqli_query($this->conn, $insert);
        $this->assertTrue($result, "Failed to insert record.");
    }

    public function testVerifyRecord()
    {
        $fname = "Test";
        $lname = "User";
        $email = "testuser@example.com";

        $query = "SELECT * FROM vipmembers WHERE fname='$fname' AND lname='$lname' AND email='$email'";
        $result = mysqli_query($this->conn, $query);
        $this->assertTrue($result && mysqli_num_rows($result) > 0, "Record not found in database.");
    }

    public function testCleanupRecord()
    {
        $fname = "Test";
        $lname = "User";
        $email = "testuser@example.com";

        $deleteQuery = "DELETE FROM vipmembers WHERE fname='$fname' AND lname='$lname' AND email='$email'";
        $result = mysqli_query($this->conn, $deleteQuery);
        $this->assertTrue($result, "Failed to delete test record.");
    }
}
