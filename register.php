<?php
include 'includes/db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];
    $address  = trim($_POST['address']);
    $phone    = trim($_POST['phone']);
    $role     = 'user';

    if (empty($name) || empty($email) || empty($password) || empty($confirm))
        $errors[] = "Please fill in all required fields.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors[] = "Invalid email format.";
    if ($password !== $confirm)
        $errors[] = "Passwords do not match.";

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) $errors[] = "Email already registered.";
    $stmt->close();

    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, address, phone, role) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssss', $name, $hashed, $hashed, $address, $phone, $role);
        // Fix: correct bind
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, address, phone, role) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssss', $name, $email, $hashed, $address, $phone, $role);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Registration successful. Please login.";
            header('Location: login.php');
            exit();
        } else {
            $errors[] = "Something went wrong. Please try again.";
        }
        $stmt->close();
    }
}

include 'includes/header.php';
?>

<div class="container">
  <h1>Create account</h1>

  <?php if (!empty($errors)): ?>
    <div class="alert-error">
      <ul style="margin:0; padding-left:1.2rem;">
        <?php foreach ($errors as $e): ?>
          <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form method="post" action="">
    <label>Name *</label>
    <input type="text" name="name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">

    <label>Email *</label>
    <input type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">

    <label>Password *</label>
    <input type="password" name="password" required>

    <label>Confirm Password *</label>
    <input type="password" name="confirm_password" required>

    <label>Address</label>
    <textarea name="address" rows="3"><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>

    <label>Phone</label>
    <input type="text" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">

    <button type="submit">Create account</button>
  </form>

  <p style="margin-top:1.25rem; font-size:0.9rem; color:var(--muted);">
    Already have an account? <a href="login.php" style="color:var(--saffron); font-weight:600;">Log in</a>
  </p>
</div>

<?php include 'includes/footer.php'; ?>