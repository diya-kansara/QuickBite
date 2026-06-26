<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

if (isset($_GET['add'])) {
    $id = (int)$_GET['add'];
    if ($id > 0) {
        $_SESSION['cart'][$id] = isset($_SESSION['cart'][$id]) ? $_SESSION['cart'][$id] + 1 : 1;
    }
    header("Location: cart.php"); exit;
}

if (isset($_GET['remove'])) {
    $id = (int)$_GET['remove'];
    unset($_SESSION['cart'][$id]);
    header("Location: cart.php"); exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quantities'])) {
    foreach ($_POST['quantities'] as $id => $qty) {
        $id = (int)$id; $qty = (int)$qty;
        if ($qty <= 0) unset($_SESSION['cart'][$id]);
        else $_SESSION['cart'][$id] = $qty;
    }
    header("Location: cart.php"); exit;
}

$cart_items = []; $total = 0;
if (!empty($_SESSION['cart'])) {
    $ids    = implode(',', array_keys($_SESSION['cart']));
    $result = $conn->query("SELECT * FROM food_items WHERE id IN ($ids)");
    while ($row = $result->fetch_assoc()) {
        $row['quantity'] = $_SESSION['cart'][$row['id']];
        $row['subtotal'] = $row['price'] * $row['quantity'];
        $total += $row['subtotal'];
        $cart_items[] = $row;
    }
}

include 'includes/header.php';
?>

<div class="container">
  <h1>Your Cart</h1>

  <?php if (empty($cart_items)): ?>
    <div class="card" style="text-align:center; padding:3rem;">
      <p style="font-size:1.1rem; color:var(--muted); margin-bottom:1.5rem;">Your cart is empty.</p>
      <a href="menu.php" class="btn-primary">Browse Menu</a>
    </div>
  <?php else: ?>
    <form method="post" action="cart.php">
      <table class="cart-table">
        <thead>
          <tr>
            <th>Dish</th>
            <th>Price (₹)</th>
            <th>Quantity</th>
            <th>Subtotal (₹)</th>
            <th>Remove</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($cart_items as $item): ?>
            <tr>
              <td><?= htmlspecialchars($item['name']) ?></td>
              <td>₹<?= number_format($item['price'], 2) ?></td>
              <td>
                <input type="number" name="quantities[<?= $item['id'] ?>]"
                  value="<?= $item['quantity'] ?>" min="1"
                  onchange="this.form.submit()">
              </td>
              <td>₹<?= number_format($item['subtotal'], 2) ?></td>
              <td><a href="cart.php?remove=<?= $item['id'] ?>">Remove</a></td>
            </tr>
          <?php endforeach; ?>
          <tr class="cart-total-row">
            <td colspan="3" style="text-align:right;"><strong>Total</strong></td>
            <td colspan="2"><strong>₹<?= number_format($total, 2) ?></strong></td>
          </tr>
        </tbody>
      </table>
    </form>

    <div style="margin-top:1.25rem;">
      <a href="checkout.php" class="btn-primary" style="padding:0.8rem 2.5rem; font-size:1rem;">
        Proceed to Checkout →
      </a>
    </div>
  <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>