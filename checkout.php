<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user info to pre-fill form
$stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user_data = $user_result->fetch_assoc();
$stmt->close();

// Redirect if cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty. <a href='menu.php'>Go back to menu</a></p>";
    include 'includes/footer.php';
    exit;
}

// Fetch cart items from DB
$cart_items = [];
$total = 0;
$ids = implode(',', array_keys($_SESSION['cart']));
$sql = "SELECT * FROM food_items WHERE id IN ($ids)";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $row['quantity'] = $_SESSION['cart'][$row['id']];
    $row['subtotal'] = $row['price'] * $row['quantity'];
    $total += $row['subtotal'];
    $cart_items[] = $row;
}
?>

<div class="checkout-container">
  <h1>Checkout</h1>

  <?php if (empty($cart_items)): ?>
    <p>Your cart is empty. <a href="menu.php">Go back to menu</a>.</p>
  <?php else: ?>

    <h2>Order Summary</h2>
    <table border="1" cellpadding="10">
      <thead>
        <tr>
          <th>Dish</th>
          <th>Price (₹)</th>
          <th>Quantity</th>
          <th>Subtotal (₹)</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($cart_items as $item): ?>
          <tr>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td><?= number_format($item['price'], 2) ?></td>
            <td><?= $item['quantity'] ?></td>
            <td><?= number_format($item['subtotal'], 2) ?></td>
          </tr>
        <?php endforeach; ?>
        <tr>
          <td colspan="3" align="right"><strong>Total:</strong></td>
          <td><strong>₹<?= number_format($total, 2) ?></strong></td>
        </tr>
      </tbody>
    </table>

    <h2>Your Details</h2>
    <form action="place_order.php" method="post">
      <label for="name">Name *</label><br>
      <input type="text" id="name" name="name" required value="<?= htmlspecialchars($user_data['name'] ?? '') ?>"><br><br>

      <label for="email">Email *</label><br>
      <input type="email" id="email" name="email" required value="<?= htmlspecialchars($user_data['email'] ?? '') ?>"><br><br>

      <label for="address">Delivery Address *</label><br>
      <textarea id="address" name="address" rows="4" required></textarea><br><br>

      <button type="submit">Place Order</button>
    </form>

  <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
