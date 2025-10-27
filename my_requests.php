<?php
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    
    // Verify the request belongs to the logged-in user
    $check_sql = "SELECT * FROM pickup_requests WHERE id = '$delete_id' AND user_id = '$user_id'";
    $check_result = mysqli_query($conn, $check_sql);
    
    if (mysqli_num_rows($check_result) > 0) {
        $delete_sql = "DELETE FROM pickup_requests WHERE id = '$delete_id' AND user_id = '$user_id'";
        if (mysqli_query($conn, $delete_sql)) {
            $_SESSION['success'] = "🗑️ Request deleted successfully!";
        } else {
            $_SESSION['error'] = "❌ Error deleting request: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['error'] = "❌ Request not found or you don't have permission to delete it.";
    }
    
    header('Location: my_requests.php');
    exit();
}

// Get user's pickup requests
$sql = "SELECT * FROM pickup_requests WHERE user_id = '$user_id' ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

// Get user stats
$total_requests = mysqli_query($conn, "SELECT COUNT(*) as count FROM pickup_requests WHERE user_id = '$user_id'")->fetch_assoc()['count'];
$pending_requests = mysqli_query($conn, "SELECT COUNT(*) as count FROM pickup_requests WHERE user_id = '$user_id' AND status = 'pending'")->fetch_assoc()['count'];
$completed_requests = mysqli_query($conn, "SELECT COUNT(*) as count FROM pickup_requests WHERE user_id = '$user_id' AND status = 'completed'")->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Requests - WastePickup</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include('header.php'); ?>
    
    <div class="container">
        <div class="dashboard-stats">
            <div class="stat-card">
                <span class="stat-number"><?php echo $total_requests; ?></span>
                <span class="stat-label">Total Requests</span>
            </div>
            <div class="stat-card">
                <span class="stat-number"><?php echo $pending_requests; ?></span>
                <span class="stat-label">Pending</span>
            </div>
            <div class="stat-card">
                <span class="stat-number"><?php echo $completed_requests; ?></span>
                <span class="stat-label">Completed</span>
            </div>
            <div class="stat-card">
                <a href="request_pickup.php" class="btn btn-primary" style="text-decoration: none;">
                    📝 New Request
                </a>
            </div>
        </div>

        <h2 style="text-align: center; margin-bottom: 2rem; color: var(--primary-dark);">My Pickup Requests</h2>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="success" style="background: var(--success); color: white; padding: 1rem; border-radius: var(--radius); margin-bottom: 2rem; text-align: center;">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error" style="background: var(--error); color: white; padding: 1rem; border-radius: var(--radius); margin-bottom: 2rem; text-align: center;">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>Waste Type</th>
                            <th>Quantity</th>
                            <th>Description</th>
                            <th>Pickup Date</th>
                            <th>Pickup Time</th>
                            <th>Status</th>
                            <th>Created Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><strong>#<?php echo $row['id']; ?></strong></td>
                            <td>
                                <span style="font-weight: 600; color: var(--primary-dark);">
                                    <?php echo ucfirst($row['waste_type']); ?>
                                </span>
                            </td>
                            <td><?php echo $row['quantity']; ?> kg</td>
                            <td><?php echo $row['description']; ?></td>
                            <td><?php echo date('M j, Y', strtotime($row['pickup_date'])); ?></td>
                            <td><?php echo date('g:i A', strtotime($row['pickup_time'])); ?></td>
                            <td>
                                <span class="status-<?php echo $row['status']; ?>">
                                    <?php echo ucfirst($row['status']); ?>
                                </span>
                            </td>
                            <td><?php echo date('M j, Y g:i A', strtotime($row['created_at'])); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <?php if ($row['status'] == 'pending' || $row['status'] == 'scheduled'): ?>
                                        <button class="btn-small delete-btn" onclick="confirmDelete(<?php echo $row['id']; ?>, '<?php echo $row['waste_type']; ?>')">
                                            🗑️ Delete
                                        </button>
                                    <?php else: ?>
                                        <span style="color: var(--text-light); font-size: 0.8rem;">Cannot delete</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 4rem; background: white; border-radius: var(--radius); box-shadow: var(--shadow);">
                <h3 style="color: var(--text-light); margin-bottom: 1rem;">No pickup requests found</h3>
                <p style="color: var(--text-light); margin-bottom: 2rem;">Start by creating your first waste pickup request</p>
                <a href="request_pickup.php" class="btn btn-primary">➕ Create First Request</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <h3 style="color: var(--error); margin-bottom: 1rem;">🗑️ Confirm Deletion</h3>
            <p>Are you sure you want to delete this pickup request?</p>
            <p style="font-weight: 600; color: var(--primary-dark);" id="requestDetails"></p>
            <p style="color: var(--error); font-size: 0.9rem; margin-top: 1rem;">
                ⚠️ This action cannot be undone!
            </p>
            <div class="modal-buttons">
                <button onclick="cancelDelete()" class="btn btn-secondary">❌ Cancel</button>
                <button onclick="proceedDelete()" class="btn btn-danger">🗑️ Delete</button>
            </div>
        </div>
    </div>
    
    <?php include('footer.php'); ?>
    
    <script>
        let deleteId = null;
        
        function confirmDelete(requestId, wasteType) {
            deleteId = requestId;
            document.getElementById('requestDetails').textContent = 'Request #' + requestId + ' - ' + wasteType;
            document.getElementById('deleteModal').style.display = 'block';
        }
        
        function cancelDelete() {
            deleteId = null;
            document.getElementById('deleteModal').style.display = 'none';
        }
        
        function proceedDelete() {
            if (deleteId) {
                window.location.href = 'my_requests.php?delete_id=' + deleteId;
            }
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('deleteModal');
            if (event.target == modal) {
                cancelDelete();
            }
        }
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                cancelDelete();
            }
        });
    </script>
</body>
</html>