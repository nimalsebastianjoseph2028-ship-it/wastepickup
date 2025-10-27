<?php
echo "<h2>Auto-Setting Up Database Tables...</h2>";

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'wastepickup';

// Connect to database
$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// SQL commands to create tables
$sql_commands = [
    // Users table
    "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        address TEXT NOT NULL,
        phone VARCHAR(20) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    
    // Pickup requests table
    "CREATE TABLE IF NOT EXISTS pickup_requests (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        waste_type VARCHAR(50) NOT NULL,
        quantity DECIMAL(10,2) NOT NULL,
        description TEXT NOT NULL,
        pickup_date DATE NOT NULL,
        pickup_time TIME NOT NULL,
        status VARCHAR(20) DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )",
    
    // Admin users table
    "CREATE TABLE IF NOT EXISTS admin_users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    
    // Insert admin user
    "INSERT IGNORE INTO admin_users (username, password) VALUES 
    ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi')",
    
    // Insert sample users
    "INSERT IGNORE INTO users (name, email, password, address, phone) VALUES 
    ('John Doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '123 Main St, City', '555-0101'),
    ('Jane Smith', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '456 Oak Ave, Town', '555-0102')"
];

// Execute each SQL command
foreach ($sql_commands as $sql) {
    if (mysqli_query($conn, $sql)) {
        echo "✓ Query executed successfully<br>";
    } else {
        echo "✗ Error: " . mysqli_error($conn) . "<br>";
    }
}

echo "<h3 style='color: green;'>Database setup completed!</h3>";
echo "<p><a href='register.php'>🚀 Test Registration Now</a></p>";

mysqli_close($conn);
?>