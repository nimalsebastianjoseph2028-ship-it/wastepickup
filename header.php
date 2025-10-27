<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<header>
    <div class="container">
        <div class="header-content">
            <div class="logo">
                <a href="index.html" style="color: white; text-decoration: none;">♻️ WastePickup</a>
            </div>
            <nav>
                <ul>
                    <li><a href="index.html">Home</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="request_pickup.php">Request Pickup</a></li>
                        <li><a href="my_requests.php">My Requests</a></li>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['admin_id'])): ?>
                        <li><a href="dashboard.php">Admin Dashboard</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
            <div class="auth-buttons">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span>Welcome, <?php echo $_SESSION['user_name']; ?></span>
                    <a href="logout.php" class="btn btn-secondary">Logout</a>
                <?php elseif (isset($_SESSION['admin_id'])): ?>
                    <span>Welcome, Admin</span>
                    <a href="logout_admin.php" class="btn btn-secondary">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-secondary">User Login</a>
                    <a href="register.php" class="btn btn-primary">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>