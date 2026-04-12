<?php

declare(strict_types=1);

require_once __DIR__ . '/functions.php';

function require_auth(): void
{
    if (!current_user()) {
        set_flash('error', 'You need to login first.');
        redirect('../auth/login.php');
    }
}

function require_admin(): void
{
    require_auth();
    if ((current_user()['role'] ?? '') !== 'admin') {
        http_response_code(403);
        exit('Access denied.');
    }
}

function require_vendor(): void
{
    require_auth();
    $role = current_user()['role'] ?? '';
    if (!in_array($role, ['vendor', 'admin'], true)) {
        http_response_code(403);
        exit('Vendor access required.');
    }
}
