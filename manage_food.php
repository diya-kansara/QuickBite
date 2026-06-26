<?php
session_start();
include 'includes/db.php';  // DB connection
include 'includes/header.php'; // Header

// Check if the user is logged in as an admin
if ($_SESSION['user_role'] !== 'admin') {
    header("Location: login.php"); // Redirect to login if not an admin
    exit;
}

// Handle Add Food Item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_food'])) {
    $food_name = trim($_POST['food_name']);
    $food_description = trim($_POST['food_description']);
    $food_price = trim($_POST['food_price']);
    $category_id = trim($_POST['category_id']);
    $food_image = $_FILES['food_image']['name'];

    // Handle Image Upload
    if ($food_image) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($food_image);
        move_uploaded_file($_FILES['food_image']['tmp_name'], $target_file);
    }

    if ($food_name && $food_description && $food_price && $category_id && $food_image) {
        // Insert into the database
        $stmt = $conn->prepare("INSERT INTO food_items (name, description, price, image, category_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdsi", $food_name, $food_description, $food_price, $food_image, $category_id);
        $stmt->execute();
        $stmt->close();
    } else {
        $error = "Please fill out all fields and upload an image.";
    }
}

// Handle Delete Food Item (via POST request)
if (isset($_POST['delete_id'])) {
    $delete_id = (int)$_POST['delete_id'];
    if ($delete_id > 0) {
        $stmt = $conn->prepare("DELETE FROM food_items WHERE id = ?");
        $stmt->bind_param('i', $delete_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Handle Edit Food Item (via POST request)
if (isset($_POST['edit_food'])) {
    $edit_id = (int)$_POST['edit_id'];
    $food_name = trim($_POST['food_name']);
    $food_description = trim($_POST['food_description']);
    $food_price = trim($_POST['food_price']);
    $category_id = trim($_POST['category_id']);
    $food_image = $_FILES['food_image']['name'];

    // Handle Image Upload
    if ($food_image) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($food_image);
        move_uploaded_file($_FILES['food_image']['tmp_name'], $target_file);
    }

    if ($food_name && $food_description && $food_price && $category_id) {
        // Update the database
        $stmt = $conn->prepare("UPDATE food_items SET name = ?, description = ?, price = ?, image = ?, category_id = ? WHERE id = ?");
        $stmt->bind_param("ssdsii", $food_name, $food_description, $food_price, $food_image, $category_id, $edit_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Fetch all food items
$sql = "SELECT * FROM food_items ORDER BY name";
$result = $conn->query($sql);
?>

<div class="container">
    <h1>Manage Food Items</h1>

    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <h2>Add New Food Item</h2>
    <form method="POST" action="manage_food.php" enctype="multipart/form-data">
        <label for="food_name">Food Name</label><br>
        <input type="text" id="food_name" name="food_name" required><br><br>

        <label for="food_description">Description</label><br>
        <textarea id="food_description" name="food_description" required></textarea><br><br>

        <label for="food_price">Price (₹)</label><br>
        <input type="number" id="food_price" name="food_price" required><br><br>

        <label for="category_id">Category</label><br>
        <select id="category_id" name="category_id" required>
            <option value="1">Pizza</option>
            <option value="2">Burgers</option>
            <option value="3">Drinks</option>
            <option value="4">Desserts</option>
            <option value="3">Salads</option>
        </select><br><br>

        <label for="food_image">Food Image</label><br>
        <input type="file" id="food_image" name="food_image" accept="image/*" required><br><br>

        <button type="submit" name="add_food" class="btn btn-primary">Add Food Item</button>
    </form>

    <h2>Food Items List</h2>
    <form method="POST" action="manage_food.php">
        <table class="food-table">
            <thead>
                <tr>
                    <th>Food Name</th>
                    <th>Price (₹)</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td>₹<?= number_format($row['price'], 2) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td><?= htmlspecialchars($row['category_id']) ?></td>
                        <td><img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" width="50"></td>
                        <td>
                            <!-- Edit Button -->
                            <button type="button" class="btn btn-secondary" onclick="openEditForm(<?= $row['id'] ?>, '<?= addslashes($row['name']) ?>', '<?= addslashes($row['description']) ?>', <?= $row['price'] ?>, <?= $row['category_id'] ?>, '<?= addslashes($row['image']) ?>')">Edit</button>

                            <!-- Delete Button -->
                            <button type="submit" name="delete_id" value="<?= $row['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item?')">Delete</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </form>
</div>

<!-- Modal for Edit Form -->
<div id="editModal" style="display:none;">
    <h2>Edit Food Item</h2>
    <form method="POST" action="manage_food.php" enctype="multipart/form-data">
        <input type="hidden" name="edit_id" id="edit_id">
        
        <label for="food_name_edit">Food Name</label><br>
        <input type="text" id="food_name_edit" name="food_name" required><br><br>

        <label for="food_description_edit">Description</label><br>
        <textarea id="food_description_edit" name="food_description" required></textarea><br><br>

        <label for="food_price_edit">Price (₹)</label><br>
        <input type="number" id="food_price_edit" name="food_price" required><br><br>

        <label for="category_id_edit">Category</label><br>
        <select id="category_id_edit" name="category_id" required>
        <option value="1">Pizza</option>
            <option value="2">Burgers</option>
            <option value="3">Drinks</option>
            <option value="4">Desserts</option>
            <option value="3">Salads</option>
        </select><br><br>

        <label for="food_image_edit">Food Image</label><br>
        <input type="file" id="food_image_edit" name="food_image" accept="image/*"><br><br>

        <button type="submit" name="edit_food" class="btn btn-primary">Save Changes</button>
        <button type="button" class="btn btn-secondary" onclick="closeEditForm()">Cancel</button>
    </form>
</div>

<script>
    // Open the edit form with pre-filled values
    function openEditForm(id, name, description, price, category, image) {
        document.getElementById('edit_id').value = id;
        document.getElementById('food_name_edit').value = name;
        document.getElementById('food_description_edit').value = description;
        document.getElementById('food_price_edit').value = price;
        document.getElementById('category_id_edit').value = category;
        document.getElementById('editModal').style.display = 'block';
    }

    // Close the edit form
    function closeEditForm() {
        document.getElementById('editModal').style.display = 'none';
    }
</script>

<?php include 'includes/footer.php'; // Footer ?>
