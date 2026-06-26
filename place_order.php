<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Not logged in, redirect to login page or show message
    header('Location: login.php');
    exit;
}

// Redirect if cart is empty
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty. <a href='menu.php'>Go back to menu</a></p>";
    include 'includes/footer.php';
    exit;
}

// Use logged in user ID
$user_id = $_SESSION['user_id'];

// Get form inputs
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$address = trim($_POST['address'] ?? '');

$errors = [];

if (!$name) $errors[] = "Name is required.";
if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
if (!$address) $errors[] = "Delivery address is required.";

if ($errors) {
    echo "<div class='container'><h2>Errors:</h2><ul>";
    foreach ($errors as $error) echo "<li>" . htmlspecialchars($error) . "</li>";
    echo "</ul><a href='checkout.php' class='btn btn-secondary'>Go back to checkout</a></div>";
    include 'includes/footer.php';
    exit;
}

// Get cart items
$cart_items = [];
$total = 0;
$ids = implode(',', array_keys($_SESSION['cart']));
$sql = "SELECT * FROM food_items WHERE id IN ($ids)";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $quantity = $_SESSION['cart'][$row['id']];
    $subtotal = $row['price'] * $quantity;
    $total += $subtotal;

    $cart_items[] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'price' => $row['price'],
        'quantity' => $quantity
    ];
}

// Insert into `orders` table including address, name, email if your DB supports these fields
$stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, status, created_at) VALUES (?, ?, 'Pending', NOW())");
$stmt->bind_param("id", $user_id, $total);
$stmt->execute();
$order_id = $stmt->insert_id;
$stmt->close();

// Insert into `order_items` table
$stmt_items = $conn->prepare("INSERT INTO order_items (order_id, food_item_id, quantity, price) VALUES (?, ?, ?, ?)");
foreach ($cart_items as $item) {
    $stmt_items->bind_param("iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
    $stmt_items->execute();
}
$stmt_items->close();

// Clear the cart
$_SESSION['cart'] = [];

?>

<div class="container">
  <h1>Order Placed Successfully!</h1>
  <p>Thank you, <?= htmlspecialchars($name) ?>. Your order has been received.</p>
  <p><strong>Order ID:</strong> #<?= $order_id ?></p>
  <p><strong>Total:</strong> ₹<?= number_format($total, 2) ?></p>
  <a href="menu.php" class="btn btn-primary">Continue Shopping</a>
</div>

<?php include 'includes/footer.php'; ?>
