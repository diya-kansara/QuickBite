<?php
include 'includes/db.php';
include 'includes/header.php';

$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $errors  = [];

    if (!$name)  $errors[] = "Name is required.";
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
    if (!$message) $errors[] = "Message is required.";

    if (!$errors) {
        $success = "Thank you, " . htmlspecialchars($name) . "! Your message has been received.";
    } else {
        $error = implode('<br>', $errors);
    }
}
?>

<div class="contact-container">
  <h1>Contact Us</h1>

  <?php if ($success): ?>
    <div class="contact-message success"><?= $success ?></div>
  <?php elseif ($error): ?>
    <div class="contact-message error"><?= $error ?></div>
  <?php endif; ?>

  <form action="" method="post" novalidate>
    <label for="name">Name *</label>
    <input type="text" id="name" name="name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">

    <label for="email">Email *</label>
    <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">

    <label for="message">Message *</label>
    <textarea id="message" name="message" rows="6" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>

    <button type="submit">Send message</button>
  </form>
</div>

<?php include 'includes/footer.php'; ?>