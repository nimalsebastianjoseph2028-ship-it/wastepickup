<?php
include('db.php');

echo "<h2>Checking Admin Password...</h2>";

// Get admin user
$sql = "SELECT * FROM admin_users WHERE username = 'admin'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $admin = mysqli_fetch_assoc($result);
    
    echo "Admin found:<br>";
    echo "Username: " . $admin['username'] . "<br>";
    echo "Password Hash: " . $admin['password'] . "<br><br>";
    
    // Test common passwords
    $passwords_to_test = ['admin123', 'admin', 'password', '123456'];
    
    foreach ($passwords_to_test as $test_password) {
        if (password_verify($test_password, $admin['password'])) {
            echo "✅ Password found: <strong>$test_password</strong><br>";
            break;
        } else {
            echo "❌ Not: $test_password<br>";
        }
    }
    
    echo "<br><a href='admin.login.php'>Try Login Again</a>";
} else {
    echo "❌ No admin user found!";
}
?>