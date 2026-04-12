<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/middleware.php';
require_auth();

if ((current_user()['role'] ?? '') === 'user') {
    upgrade_to_vendor((int) current_user()['id']);
    set_flash('success', 'You are now a vendor.');
}

redirect('dashboard.php');
