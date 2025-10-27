<?php
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $waste_type = mysqli_real_escape_string($conn, $_POST['waste_type']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $pickup_date = mysqli_real_escape_string($conn, $_POST['pickup_date']);
    $pickup_time = mysqli_real_escape_string($conn, $_POST['pickup_time']);
    
    $sql = "INSERT INTO pickup_requests (user_id, waste_type, quantity, description, pickup_date, pickup_time) 
            VALUES ('$user_id', '$waste_type', '$quantity', '$description', '$pickup_date', '$pickup_time')";
    
    if (mysqli_query($conn, $sql)) {
        $success = "Pickup request submitted successfully!";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Request Pickup - WastePickup</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include('header.php'); ?>
    
    <div class="form-container">
        <h2>Request Waste Pickup</h2>
        
        <?php if (isset($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>Waste Type:</label>
                <select name="waste_type" required>
                    <option value="">Select Waste Type</option>
                    <option value="general">General Waste</option>
                    <option value="recyclable">Recyclable</option>
                    <option value="hazardous">Hazardous</option>
                    <option value="organic">Organic</option>
                    <option value="e-waste">E-Waste</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Quantity (kg):</label>
                <input type="number" name="quantity" min="1" required>
            </div>
            
            <div class="form-group">
                <label>Description:</label>
                <textarea name="description" required></textarea>
            </div>
            
            <div class="form-group">
                <label>Preferred Pickup Date:</label>
                <input type="date" name="pickup_date" required>
            </div>
            
            <div class="form-group">
                <label>Preferred Pickup Time:</label>
                <input type="time" name="pickup_time" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Submit Request</button>
        </form>
    </div>
    
    <?php include('footer.php'); ?>
    <script src="script.js"></script>
</body>
</html>