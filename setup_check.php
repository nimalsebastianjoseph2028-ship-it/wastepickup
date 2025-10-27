<?php
echo "<h2>WastePickup Setup Check</h2>";

// Check 1: Server running
echo "<h3>1. Server Check:</h3>";
echo "✓ PHP is working<br>";

// Check 2: Folder location
echo "<h3>2. Folder Check:</h3>";
$current_file = __FILE__;
echo "Current file: " . $current_file . "<br>";
if (strpos($current_file, 'wastepickup') !== false) {
    echo "✓ Files are in wastepickup folder<br>";
} else {
    echo "✗ Files are NOT in wastepickup folder<br>";
}

// Check 3: Database
echo "<h3>3. Database Check:</h3>";
$conn = mysqli_connect('localhost', 'root', '', 'wastepickup');
if ($conn) {
    echo "✓ Database connected<br>";
    
    // Check tables
    $tables = ['users', 'pickup_requests', 'admin_users'];
    foreach ($tables as $table) {
        $result = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
        if (mysqli_num_rows($result) > 0) {
            echo "✓ Table '$table' exists<br>";
        } else {
            echo "✗ Table '$table' missing<br>";
        }
    }
} else {
    echo "✗ Database connection failed<br>";
}

echo "<h3>4. Quick Links:</h3>";
echo "<a href='index.html'>Home Page</a><br>";
echo "<a href='admin.login.php'>Admin Login</a><br>";
echo "<a href='register.php'>User Registration</a><br>";
echo "<a href='login.php'>User Login</a><br>";
?>