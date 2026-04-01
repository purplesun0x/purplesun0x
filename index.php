<?php
require __DIR__ . '/init.php';
require __DIR__ . '/layout.php';

$user = currentUser($db);
renderHeader('Welcome', $user);
?>
<h1>Sell Digital Products in Minutes</h1>
<p>This platform includes user & admin flows, product checkout, and a referral system.</p>
<div class="grid mt-2">
    <div class="card">
        <span class="badge">User</span>
        <h3>Account & Dashboard</h3>
        <p>Create an account, purchase products, and access your downloadable files anytime.</p>
    </div>
    <div class="card">
        <span class="badge">Admin</span>
        <h3>Product Management</h3>
        <p>Admin can create products, see users, and monitor store activity from one dashboard.</p>
    </div>
    <div class="card">
        <span class="badge">Referral</span>
        <h3>Earn Commissions</h3>
        <p>Every user gets a referral code. Earn 10% commission from referred purchases.</p>
    </div>
</div>
<p class="mt-2"><a class="btn" href="/store.php">Browse Store</a></p>
<?php renderFooter(); ?>
