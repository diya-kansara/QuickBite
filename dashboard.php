<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
?>

<div class="dashboard-container">
  <h1>Dashboard</h1>

  <div class="dashboard-menu">
    <div class="dashboard-card">
      <h2>Total Orders</h2>
      <?php
        $r = $conn->query("SELECT COUNT(id) AS c FROM orders");
        echo "<p>" . $r->fetch_assoc()['c'] . "</p>";
      ?>
      <a href="orders.php" class="btn-primary">View Orders</a>
    </div>

    <div class="dashboard-card">
      <h2>Total Users</h2>
      <?php
        $r = $conn->query("SELECT COUNT(id) AS c FROM users");
        echo "<p>" . $r->fetch_assoc()['c'] . "</p>";
      ?>
      <a href="users.php" class="btn-primary">Manage Users</a>
    </div>

    <div class="dashboard-card">
      <h2>Food Items</h2>
      <?php
        $r = $conn->query("SELECT COUNT(id) AS c FROM food_items");
        echo "<p>" . $r->fetch_assoc()['c'] . "</p>";
      ?>
      <a href="manage_food.php" class="btn-primary">Manage Food</a>
    </div>

    <div class="dashboard-card">
      <h2>Messages</h2>
      <?php
        $r = $conn->query("SHOW TABLES LIKE 'contact_messages'");
        if ($r->num_rows > 0) {
            $r2 = $conn->query("SELECT COUNT(id) AS c FROM contact_messages");
            echo "<p>" . $r2->fetch_assoc()['c'] . "</p>";
        } else {
            echo "<p>0</p>";
        }
      ?>
      <a href="view_messages.php" class="btn-primary">View Messages</a>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>