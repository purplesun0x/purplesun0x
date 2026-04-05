<?php
$featuredProducts = [
    ['name' => 'Aether Smartwatch', 'price' => 349, 'badge' => 'Best Seller'],
    ['name' => 'Nova Earbuds Pro', 'price' => 199, 'badge' => 'New'],
    ['name' => 'Lumen Desk Lamp', 'price' => 129, 'badge' => 'Eco'],
    ['name' => 'Orbit Carry Pack', 'price' => 159, 'badge' => 'Limited'],
    ['name' => 'Pulse Fitness Band', 'price' => 89, 'badge' => 'Trending'],
    ['name' => 'Arc Wireless Charger', 'price' => 69, 'badge' => 'Fast Charge'],
];

$categories = [
    ['title' => 'Wearables', 'description' => 'Elegant performance essentials.'],
    ['title' => 'Home Tech', 'description' => 'Minimal devices for modern spaces.'],
    ['title' => 'Audio', 'description' => 'Immersive sound and premium comfort.'],
    ['title' => 'Accessories', 'description' => 'Refined utility for daily life.'],
];

$testimonials = [
    ['name' => 'Lara M.', 'quote' => 'Every touchpoint feels intentional. Checkout and delivery are seamless.'],
    ['name' => 'Jay R.', 'quote' => 'The referral rewards are transparent and actually worth sharing.'],
    ['name' => 'Nina T.', 'quote' => 'A premium shopping experience from homepage to order tracking.'],
];

$cartCount = 3;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Luxora Commerce</title>
    <link rel="stylesheet" href="assets/styles.css" />
</head>
<body>
<header class="topbar">
    <div class="container nav-wrap">
        <a class="brand" href="#">Luxora</a>
        <nav class="nav-links">
            <a href="#">Home</a>
            <a href="#shop">Shop</a>
            <a href="#categories">Categories</a>
            <a href="#deals">Deals</a>
            <a href="#contact">Contact</a>
            <a href="#">Login/Register</a>
            <a class="cart" href="#">Cart <span><?= $cartCount ?></span></a>
        </nav>
        <button class="icon-btn" data-theme-toggle aria-label="Toggle dark mode">◐</button>
    </div>
</header>

<main>
    <section class="hero">
        <div class="container hero-grid">
            <div>
                <p class="eyebrow">PREMIUM DIGITAL MARKETPLACE</p>
                <h1>Designed to Convert.<br/>Built to Scale.</h1>
                <p class="subtle">Launch and grow a high-end e-commerce brand with intelligent merchandising, seamless checkout, and an integrated referral growth engine.</p>
                <div class="actions">
                    <a class="btn primary" href="#shop">Shop Now</a>
                    <a class="btn ghost" href="#deals">Explore Deals</a>
                </div>
            </div>
            <div class="hero-card">
                <h3>Today Highlights</h3>
                <ul>
                    <li>Express delivery in 28 cities</li>
                    <li>Instant checkout with saved wallets</li>
                    <li>Referral cashback up to 18%</li>
                </ul>
            </div>
        </div>
    </section>

    <section id="shop" class="section container">
        <div class="section-head">
            <h2>Featured Products</h2>
            <a href="#" class="text-link">View all</a>
        </div>
        <div class="product-grid">
            <?php foreach ($featuredProducts as $product): ?>
                <article class="card product-card">
                    <div class="badge"><?= htmlspecialchars($product['badge']) ?></div>
                    <div class="product-thumb"></div>
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p>$<?= number_format($product['price'], 2) ?></p>
                    <div class="hover-actions">
                        <button class="btn small">Quick Add</button>
                        <button class="btn small ghost">♡ Wishlist</button>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section id="categories" class="section container">
        <div class="section-head"><h2>Shop by Category</h2></div>
        <div class="category-grid">
            <?php foreach ($categories as $category): ?>
                <article class="card tile">
                    <h3><?= htmlspecialchars($category['title']) ?></h3>
                    <p><?= htmlspecialchars($category['description']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section id="deals" class="section container">
        <div class="banner">
            <div>
                <p class="eyebrow">LIMITED OFFER</p>
                <h3>Spring Upgrade Event — Save up to 40%</h3>
            </div>
            <button class="btn primary" data-modal-open>Claim Offer</button>
        </div>
    </section>

    <section class="section container">
        <div class="section-head"><h2>What customers say</h2></div>
        <div class="testimonial-grid">
            <?php foreach ($testimonials as $testimonial): ?>
                <blockquote class="card quote">
                    <p>“<?= htmlspecialchars($testimonial['quote']) ?>”</p>
                    <cite><?= htmlspecialchars($testimonial['name']) ?></cite>
                </blockquote>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<footer class="footer" id="contact">
    <div class="container footer-grid">
        <p>© <?= date('Y') ?> Luxora Commerce</p>
        <div>
            <a href="#">About</a>
            <a href="#">Terms</a>
            <a href="#">Privacy</a>
            <a href="#">Support</a>
        </div>
    </div>
</footer>

<div class="modal" data-modal>
    <div class="modal-card">
        <h3>Offer Activated</h3>
        <p>Your deal is now saved to your account.</p>
        <button class="btn primary" data-modal-close>Continue Shopping</button>
    </div>
</div>
<script src="assets/app.js"></script>
</body>
</html>
