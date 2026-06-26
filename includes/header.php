<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>QuickBite</title>
  <link rel="stylesheet" href="css/style.css" />
</head>
<body>

<header class="navbar">
  <a href="index.php" class="logo">QuickBite</a>
  <nav>
    <?php if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin'): ?>
      <a href="index.php">Home</a>
      <a href="menu.php">Menu</a>
      <a href="cart.php">Cart</a>
      <a href="contact.php">Contact</a>
    <?php endif; ?>

    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'admin'): ?>
      <a href="dashboard.php">Dashboard</a>
    <?php elseif (isset($_SESSION['user_id'])): ?>
      <a href="orders.php">My Orders</a>
    <?php endif; ?>

    <?php if (!isset($_SESSION['user_id'])): ?>
      <a href="login.php">Login</a>
      <a href="register.php">Register</a>
    <?php else: ?>
      <a href="logout.php">Logout (<?= htmlspecialchars($_SESSION['user_name']) ?>)</a>
    <?php endif; ?>
  </nav>
</header>