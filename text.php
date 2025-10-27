<?php
echo "<h1>WastePickup System - Test Page</h1>";
echo "<p>If you can see this, your server is working!</p>";
echo "<h3>Available Pages:</h3>";
echo "<ul>";
echo "<li><a href='index.html'>Home Page</a></li>";
echo "<li><a href='admin.login.php'>Admin Login</a></li>";
echo "<li><a href='login.php'>User Login</a></li>";
echo "<li><a href='register.php'>User Registration</a></li>";
echo "</ul>";

// Check database connection
echo "<h3>Database Check:</h3>";
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'wastepickup';

$conn = mysqli_connect($host, $username, $password, $database);
if ($conn) {
    echo "✓ Database connected successfully<br>";
} else {
    echo "✗ Database connection failed: " . mysqli_connect_error() . "<br>";
}
?>