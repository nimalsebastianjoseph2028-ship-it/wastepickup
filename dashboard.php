<?php
include('db.php');

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin.login.php');
    exit();
}

// Get statistics
$users_count = mysqli_query($conn, "SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$requests_count = mysqli_query($conn, "SELECT COUNT(*) as count FROM pickup_requests")->fetch_assoc()['count'];
$pending_requests = mysqli_query($conn, "SELECT COUNT(*) as count FROM pickup_requests WHERE status='pending'")->fetch_assoc()['count'];
$completed_requests = mysqli_query($conn, "SELECT COUNT(*) as count FROM pickup_requests WHERE status='completed'")->fetch_assoc()['count'];

// Get all pickup requests
$sql = "SELECT pr.*, u.name as user_name, u.email as user_email 
        FROM pickup_requests pr 
        JOIN users u ON pr.user_id = u.id 
        ORDER BY pr.created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - WastePickup</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include('header.php'); ?>
    
    <div class="container">
        <h2>Admin Dashboard</h2>
        
        <div class="dashboard-stats">
            <div class="stat-card">
                <span class="stat-number"><?php echo $users_count; ?></span>
                <span class="stat-label">Total Users</span>
            </div>
            <div class="stat-card">
                <span class="stat-number"><?php echo $requests_count; ?></span>
                <span class="stat-label">Total Requests</span>
            </div>
            <div class="stat-card">
                <span class="stat-number"><?php echo $pending_requests; ?></span>
                <span class="stat-label">Pending Requests</span>
            </div>
            <div class="stat-card">
                <span class="stat-number"><?php echo $completed_requests; ?></span>
                <span class="stat-label">Completed Requests</span>
            </div>
        </div>

        <h3>All Pickup Requests</h3>
        
        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Waste Type</th>
                            <th>Quantity</th>
                            <th>Pickup Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td>#<?php echo $row['id']; ?></td>
                            <td><?php echo $row['user_name']; ?><br><small><?php echo $row['user_email']; ?></small></td>
                            <td><?php echo ucfirst($row['waste_type']); ?></td>
                            <td><?php echo $row['quantity']; ?> kg</td>
                            <td><?php echo date('M j, Y', strtotime($row['pickup_date'])); ?></td>
                            <td class="status-<?php echo $row['status']; ?>">
                                <?php echo ucfirst($row['status']); ?>
                            </td>
                            <td>
                                <a href="update_status.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Update Status</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>No pickup requests found.</p>
        <?php endif; ?>
    </div>
    
    <?php include('footer.php'); ?>
</body>
</html>