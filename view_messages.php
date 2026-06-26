<?php
session_start();
include 'includes/db.php';  // Correct path to db.php
include 'includes/header.php'; // Correct path to header.php

// Check if the user is logged in as an admin
if ($_SESSION['user_role'] !== 'admin') {
    header("Location: login.php"); // Redirect to login if not an admin
    exit;
}

// Fetch all contact messages from the database
$sql = "SELECT id, name, email, message, created_at FROM contact_messages ORDER BY created_at DESC";
$result = $conn->query($sql); // Execute the query
?>

<div class="view-messages-container">
    <h1>Contact Messages</h1>

    <?php if ($result->num_rows > 0): ?>
        <table class="messages-table">
            <thead>
                <tr>
                    <th>Message ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
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
                        <td><?= htmlspecialchars($row['message']) ?></td>
                        <td><?= $row['created_at'] ?></td>
                        <td>
                            <!-- Delete Button -->
                            <form action="view_messages.php" method="POST" style="display:inline;">
                                <input type="hidden" name="message_id" value="<?= $row['id'] ?>">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this message?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No messages found.</p>
    <?php endif; ?>

</div>

<?php
// Handle message deletion on button click
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message_id'])) {
    $message_id = $_POST['message_id'];
    // Delete the message from the database
    $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = ?");
    $stmt->bind_param('i', $message_id);
    if ($stmt->execute()) {
        echo "<p>Message deleted successfully!</p>";
        // Redirect to reload the page and refresh the message list
        header("Location: view_messages.php");
        exit;
    } else {
        echo "<p>Error deleting message.</p>";
    }
    $stmt->close();
}

include 'includes/footer.php'; // Footer
?>
