<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>PHP Web Application</title>
</head>
<body>
    <h1>Add New Member</h1>
    <form action="member_add.php" method="post">
        <label for="fname">First Name:</label>
        <input type="text" id="fname" name="fname"><br><br>
        
        <label for="lname">Last Name:</label>
        <input type="text" id="lname" name="lname"><br><br>
        
        <label for="gender">Gender (M/F):</label>
        <input type="text" id="gender" name="gender" pattern="[MF]" title="Enter 'M' for Male or 'F' for Female"><br><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email"><br><br>
        
        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone"><br><br>
        
        <input type="submit" value="Submit">
        <input type="reset" value="Reset">
    </form>
    <a href="vip_member.php">Return to Home Page</a>
</body>
</html>