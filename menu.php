<?php
include 'includes/db.php'; // Ensure the database connection is included
include 'includes/header.php'; // Include the header

// Fetch categories from the database
$cat_query = "SELECT id, name FROM categories ORDER BY name";
$cat_result = $conn->query($cat_query);

// Get the selected category ID from URL (if any)
$selected_cat = isset($_GET['category']) ? (int)$_GET['category'] : 0;

// Fetch food items based on selected category
if ($selected_cat > 0) {
    $stmt = $conn->prepare("SELECT * FROM food_items WHERE category_id = ? ORDER BY name");
    $stmt->bind_param("i", $selected_cat);
    $stmt->execute();
    $food_result = $stmt->get_result();
} else {
    // Fetch all food items if no category is selected
    $food_result = $conn->query("SELECT * FROM food_items ORDER BY name");
}
?>

<div class="container">
    <h1>Our Menu</h1>

    <!-- Category Filter Buttons -->
    <div class="category-filters">
        <a href="menu.php" class="btn <?= $selected_cat === 0 ? 'btn-primary' : '' ?>">All</a>
        <?php while ($cat = $cat_result->fetch_assoc()) : ?>
            <a href="menu.php?category=<?= (int)$cat['id'] ?>" class="btn <?= $selected_cat === (int)$cat['id'] ? 'btn-primary' : '' ?>">
                <?= htmlspecialchars($cat['name']) ?>
            </a>
        <?php endwhile; ?>
    </div>

    <!-- Food Items Grid -->
    <div class="dishes-container">
        <?php if ($food_result && $food_result->num_rows > 0): ?>
            <?php while ($food = $food_result->fetch_assoc()): ?>
                <div class="dish-card">
                 <img src="<?= htmlspecialchars($food['image']) ?>" alt="<?= htmlspecialchars($food['name']) ?>">


                    <h3><?= htmlspecialchars($food['name']) ?></h3>
                    <p><?= htmlspecialchars($food['description']) ?></p>
                    <p><strong>₹<?= number_format($food['price'], 2) ?></strong></p>
                    <a href="cart.php?add=<?= (int)$food['id'] ?>" class="btn btn-primary">Add to Cart</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No items found in this category.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; // Include footer ?>
