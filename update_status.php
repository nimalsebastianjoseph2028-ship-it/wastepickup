<?php
include('db.php');

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin.login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit();
}

$request_id = $_GET['id'];

// Get request details
$sql = "SELECT * FROM pickup_requests WHERE id='$request_id'";
$result = mysqli_query($conn, $sql);
$request = mysqli_fetch_assoc($result);

if (!$request) {
    header('Location: dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    $update_sql = "UPDATE pickup_requests SET status='$status' WHERE id='$request_id'";
    
    if (mysqli_query($conn, $update_sql)) {
        $success = "Status updated successfully!";
        // Refresh request data
        $request['status'] = $status;
    } else {
        $error = "Error updating status: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Status - WastePickup</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include('header.php'); ?>
    
    <div class="form-container">
        <h2>Update Request Status</h2>
        
        <?php if (isset($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div style="margin-bottom: 2rem;">
            <h3>Request Details</h3>
            <p><strong>Request ID:</strong> #<?php echo $request['id']; ?></p>
            <p><strong>Waste Type:</strong> <?php echo ucfirst($request['waste_type']); ?></p>
            <p><strong>Quantity:</strong> <?php echo $request['quantity']; ?> kg</p>
            <p><strong>Current Status:</strong> <span class="status-<?php echo $request['status']; ?>"><?php echo ucfirst($request['status']); ?></span></p>
        </div>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>Update Status:</label>
                <select name="status" required>
                    <option value="pending" <?php echo $request['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="scheduled" <?php echo $request['status'] == 'scheduled' ? 'selected' : ''; ?>>Scheduled</option>
                    <option value="completed" <?php echo $request['status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                    <option value="cancelled" <?php echo $request['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Update Status</button>
            <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </form>
    </div>
    
    <?php include('footer.php'); ?>
</body>
</html>