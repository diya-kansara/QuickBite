<?php 

include 'includes/header.php'; ?>

<section class="hero" style="background-image: url('images/hero-bg.jpg');">
  <div class="hero-content">
    <h1>Welcome to QuickBite</h1>
    <p>Delicious meals delivered to your doorstep</p>
    <a href="menu.php" class="btn-primary">Order Now</a>
  </div>
</section>

<section class="features">
  <div class="feature">
    <h2>Fresh Ingredients</h2>
    <p>We use only fresh and high-quality ingredients in all our dishes.</p>
  </div>
  <div class="feature">
    <h2>Fast Delivery</h2>
    <p>Quick and reliable delivery to get your food hot and fresh.</p>
  </div>
  <div class="feature">
    <h2>Easy Payment</h2>
    <p>Multiple payment options for your convenience.</p>
  </div>
</section>

<section class="featured-dishes">
  <h2>Featured Dishes</h2>
  <div class="dishes-container">
    <div class="dish-card">
      <a href="menu.php#pizza">
        <img src="images/margherita.jpg" alt="Margherita Pizza">
        <h3>Margherita Pizza</h3>
        <p>Classic delight with fresh basil and mozzarella.</p>
      </a>
    </div>
    <div class="dish-card">
      <a href="menu.php#burger">
        <img src="images/veggie_burger.jpg" alt="Veggie Burger">
        <h3>Veggie Burger</h3>
        <p>Grilled veggies with a flavorful sauce in a bun.</p>
      </a>
    </div>
    <div class="dish-card">
      <a href="menu.php#dessert">
        <img src="images/chocolate_cake.jpg" alt="Chocolate Cake">
        <h3>Chocolate Cake</h3>
        <p>Rich, moist chocolate cake to satisfy your sweet tooth.</p>
      </a>
    </div>
  </div>

  <div class="explore-btn-container">
    <a href="menu.php" class="btn-secondary">Explore Full Menu</a>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
