<?php
$featuredProducts = [
    ['name' => 'Aurora Smartwatch', 'price' => '$299', 'tag' => 'Best Seller'],
    ['name' => 'Vertex Noise-Canceling Headphones', 'price' => '$399', 'tag' => 'New Arrival'],
    ['name' => 'Nimbus Home Speaker', 'price' => '$249', 'tag' => 'Limited'],
    ['name' => 'Pulse Fitness Ring', 'price' => '$199', 'tag' => 'Top Rated'],
    ['name' => 'Lumen Desk Lamp Pro', 'price' => '$149', 'tag' => 'Editor\'s Pick'],
    ['name' => 'Slate Wireless Charger', 'price' => '$89', 'tag' => 'Popular'],
];

$categories = [
    ['title' => 'Wearables', 'desc' => 'Precision tech for daily performance.'],
    ['title' => 'Audio', 'desc' => 'Immersive sound with elegant design.'],
    ['title' => 'Smart Home', 'desc' => 'Elevate every room with automation.'],
    ['title' => 'Accessories', 'desc' => 'Minimal essentials crafted to last.'],
];

$testimonials = [
    ['name' => 'Maya D.', 'role' => 'Founder', 'quote' => 'A flawless shopping experience. Fast checkout, premium packaging, and quality products.'],
    ['name' => 'Daniel R.', 'role' => 'Product Designer', 'quote' => 'The UI feels world-class and the referral rewards make me keep coming back.'],
    ['name' => 'Sofia K.', 'role' => 'Marketer', 'quote' => 'I found my entire tech setup here. Smooth browsing and trustworthy delivery updates.'],
];

$cartCount = 3;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NovaMarket | Premium Commerce</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
<header class="top-nav sticky glass">
    <div class="container nav-inner">
        <a class="brand" href="index.php">NovaMarket</a>
        <nav class="nav-links" id="mainNav">
            <a href="#">Home</a>
            <a href="#featured">Shop</a>
            <a href="#categories">Categories</a>
            <a href="#deals">Deals</a>
            <a href="#contact">Contact</a>
            <a href="dashboard.php">Login/Register</a>
            <a href="#" class="cart-link">Cart <span class="badge"><?= $cartCount ?></span></a>
        </nav>
        <button class="icon-btn" data-toggle="mobile-nav" aria-label="Toggle navigation">☰</button>
    </div>
</header>

<main>
    <section class="hero">
        <div class="container hero-grid">
            <div>
                <p class="eyebrow">High-growth marketplace platform</p>
                <h1>Premium commerce experience built for modern shoppers.</h1>
                <p class="muted">Scale your lifestyle with carefully curated products, instant deals, and a referral system that rewards every recommendation.</p>
                <div class="button-row">
                    <a href="#featured" class="btn btn-primary">Shop Now</a>
                    <a href="#deals" class="btn btn-secondary">Explore Deals</a>
                </div>
            </div>
            <div class="hero-card surface">
                <h3>Live marketplace metrics</h3>
                <ul>
                    <li><span>Conversion lift</span><strong>+38%</strong></li>
                    <li><span>Avg. delivery time</span><strong>1.8 days</strong></li>
                    <li><span>Referral payout rate</span><strong>99.2%</strong></li>
                </ul>
            </div>
        </div>
    </section>

    <section class="promo-banner" id="deals">
        <div class="container surface banner-inner">
            <div>
                <h2>Spring Elite Deal Event</h2>
                <p>Save up to 45% on selected premium electronics and earn 2x referral commissions this week.</p>
            </div>
            <button class="btn btn-primary" data-open-modal="dealModal">Claim Offer</button>
        </div>
    </section>

    <section class="section" id="featured">
        <div class="container">
            <div class="section-heading">
                <h2>Featured Products</h2>
                <p>Handpicked catalog with conversion-optimized cards and quick actions.</p>
            </div>
            <div class="product-grid">
                <?php foreach ($featuredProducts as $product): ?>
                    <article class="product-card surface">
                        <span class="tag"><?= htmlspecialchars($product['tag']) ?></span>
                        <div class="thumb"></div>
                        <h3><?= htmlspecialchars($product['name']) ?></h3>
                        <p class="price"><?= htmlspecialchars($product['price']) ?></p>
                        <div class="card-actions">
                            <button class="btn btn-small btn-primary">Quick Add</button>
                            <button class="btn btn-small btn-ghost">♡ Wishlist</button>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section" id="categories">
        <div class="container">
            <div class="section-heading">
                <h2>Browse by Category</h2>
                <p>Visual product discovery engineered for speed and clarity.</p>
            </div>
            <div class="category-grid">
                <?php foreach ($categories as $category): ?>
                    <article class="category-tile surface">
                        <h3><?= htmlspecialchars($category['title']) ?></h3>
                        <p><?= htmlspecialchars($category['desc']) ?></p>
                        <a href="#">Explore →</a>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section testimonials">
        <div class="container">
            <div class="section-heading">
                <h2>Trusted by modern buyers</h2>
                <p>Social proof that reinforces trust at checkout.</p>
            </div>
            <div class="testimonial-grid">
                <?php foreach ($testimonials as $testimonial): ?>
                    <blockquote class="surface">
                        <p>“<?= htmlspecialchars($testimonial['quote']) ?>”</p>
                        <footer>
                            <strong><?= htmlspecialchars($testimonial['name']) ?></strong>
                            <span><?= htmlspecialchars($testimonial['role']) ?></span>
                        </footer>
                    </blockquote>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</main>

<footer class="footer" id="contact">
    <div class="container footer-grid">
        <div>
            <h4>NovaMarket</h4>
            <p>Premium digital marketplace for ambitious lifestyles.</p>
        </div>
        <div>
            <h5>Company</h5>
            <a href="#">About</a>
            <a href="#">Careers</a>
            <a href="#">Press</a>
        </div>
        <div>
            <h5>Support</h5>
            <a href="#">Help Center</a>
            <a href="#">Shipping</a>
            <a href="#">Returns</a>
        </div>
        <div>
            <h5>Legal</h5>
            <a href="#">Privacy</a>
            <a href="#">Terms</a>
            <a href="#">Cookies</a>
        </div>
    </div>
</footer>

<div class="modal" id="dealModal" aria-hidden="true">
    <div class="modal-content surface">
        <button class="icon-btn modal-close" data-close-modal="dealModal">✕</button>
        <h3>Offer Activated</h3>
        <p>Your 45% elite discount has been added. Continue to shop and invite friends to unlock bonus credits.</p>
        <button class="btn btn-primary" data-close-modal="dealModal">Continue Shopping</button>
    </div>
</div>

<script src="assets/app.js"></script>
</body>
</html>
