<?php
require_once __DIR__ . '/../includes/auth.php';
logout_user();
set_flash('success', 'Logged out successfully.');
redirect('../public/index.php');
