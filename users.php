<?php
session_start();
include 'includes/db.php'; // DB connection
include 'includes/header.php'; // Header

// Check if the user is logged in as an admin
if ($_SESSION['user_role'] !== 'admin') {
    header("Location: login.php"); // Redirect to login if not an admin
    exit;
}

// Handle delete user action
if (isset($_POST['delete_user_id'])) {
    $user_id = $_POST['delete_user_id'];

    // Prepare and execute delete query
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        echo "<p>User deleted successfully.</p>";
    } else {
        echo "<p>Error deleting user.</p>";
    }
    $stmt->close();
}

// Fetch all users from the database
$sql = "SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC";
$result = $conn->query($sql); // Directly execute the query
?>

<div class="manage-users-container">
    <h1>Manage Users</h1>

    <?php if ($result->num_rows > 0): ?>
        <table class="users-table">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>#<?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['role']) ?></td>
                        <td><?= $row['created_at'] ?></td>
                        <td>
                            <!-- Delete User -->
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="delete_user_id" value="<?= $row['id'] ?>">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No users found.</p>
    <?php endif; ?>

</div>

<?php
include 'includes/footer.php'; // Footer
?>
