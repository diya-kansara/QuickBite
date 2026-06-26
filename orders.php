<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

$user_id = $_SESSION['user_id'] ?? null;
$role = $_SESSION['user_role'] ?? 'user';

if (!$user_id) {
    echo "<p>You must be logged in to view your orders. <a href='login.php'>Login here</a></p>";
    include 'includes/footer.php';
    exit;
}

// ✅ Handle status update if admin and form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status']) && $role === 'admin') {
    $order_id = (int)$_POST['order_id'];
    $new_status = $_POST['status'];

    // Update the order status in the database
    $update_stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $update_stmt->bind_param("si", $new_status, $order_id);
    $update_stmt->execute();
    $update_stmt->close();
}

// ✅ Handle delete order if admin and form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_order']) && $role === 'admin') {
    $order_id = (int)$_POST['order_id'];

    $delete_stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $delete_stmt->bind_param("i", $order_id);
    $delete_stmt->execute();
    $delete_stmt->close();
}

// ✅ Fetch orders
if ($role === 'admin') {
    $sql = "SELECT o.id, o.total_amount, o.status, o.created_at, u.name AS user_name 
            FROM orders o
            LEFT JOIN users u ON o.user_id = u.id
            ORDER BY o.created_at DESC";
    $stmt = $conn->prepare($sql);
} else {
    $sql = "SELECT o.id, o.total_amount, o.status, o.created_at, u.name AS user_name 
            FROM orders o
            LEFT JOIN users u ON o.user_id = u.id
            WHERE o.user_id = ? 
            ORDER BY o.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $user_id);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container">
    <h1>Order History</h1>

    <?php if ($result->num_rows > 0): ?>
        <table class="orders-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User</th>
                    <th>Total Amount (₹)</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <?php if ($role === 'admin'): ?>
                        <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>#<?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['user_name'] ?? 'N/A') ?></td>
                        <td>₹<?= number_format($row['total_amount'], 2) ?></td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                        <td><?= htmlspecialchars($row['created_at']) ?></td>

                        <?php if ($role === 'admin'): ?>
                            <td>
                                <!-- ✅ Update Status Form -->
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                                    <select name="status" required>
                                        <option value="Pending" <?= $row['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="Processing" <?= $row['status'] == 'Processing' ? 'selected' : '' ?>>Processing</option>
                                        <option value="Delivered" <?= $row['status'] == 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                                         <option value="Canceled" <?= $row['status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                    </select>
                                    <button type="submit" name="update_status" class="btn btn-secondary">Update</button>
                                </form>

                                <!-- ✅ Delete Order Form -->
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                                    <button type="submit" name="delete_order" class="btn btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this order?')">Delete</button>
                                </form>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No orders found.</p>
    <?php endif; ?>
</div>

<?php
$stmt->close();
include 'includes/footer.php';
?>
