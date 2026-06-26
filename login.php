<?php
include 'includes/db.php';
include 'includes/header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email      = trim($_POST['email']);
    $password   = $_POST['password'];
    $login_type = $_POST['login_type'];

    if ($email && $password && $login_type) {
        if ($login_type === 'user') {
            $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
        } else {
            $stmt = $conn->prepare("SELECT id, name, password FROM admins WHERE email = ?");
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $login_type;
                header("Location: " . ($login_type === 'admin' ? 'dashboard.php' : 'orders.php'));
                exit;
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "No account found with that email.";
        }
    } else {
        $error = "All fields are required.";
    }
}
?>

<div class="container">
  <h1>Welcome back</h1>

  <?php if ($error): ?>
    <div class="alert-error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="post" action="login.php">
    <label for="email">Email</label>
    <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">

    <label for="password">Password</label>
    <input type="password" id="password" name="password" required>

    <label for="login_type">Login as</label>
    <select id="login_type" name="login_type" required>
      <option value="user">User</option>
      <option value="admin">Admin</option>
    </select>

    <button type="submit">Log in</button>
  </form>

  <p style="margin-top:1.25rem; font-size:0.9rem; color:var(--muted);">
    Don't have an account? <a href="register.php" style="color:var(--saffron); font-weight:600;">Register here</a>
  </p>
</div>

<?php include 'includes/footer.php'; ?>