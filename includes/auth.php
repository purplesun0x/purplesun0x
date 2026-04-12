<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';

function register_user(string $name, string $email, string $password): array
{
    $pdo = db();

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return [false, 'Invalid email address.'];
    }

    if (strlen($password) < 6) {
        return [false, 'Password must be at least 6 characters.'];
    }

    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        return [false, 'Email is already in use.'];
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (name, email, password, role, created_at) VALUES (?, ?, ?, "user", NOW())');
    $stmt->execute([$name, $email, $hash]);

    return [true, 'Registration successful. Please login.'];
}

function login_user(string $email, string $password): array
{
    $pdo = db();
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        return [false, 'Invalid credentials.'];
    }

    $_SESSION['user'] = [
        'id' => (int) $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'role' => $user['role'],
    ];

    return [true, 'Login successful.'];
}

function logout_user(): void
{
    unset($_SESSION['user']);
}

function upgrade_to_vendor(int $userId): void
{
    $pdo = db();
    $stmt = $pdo->prepare('UPDATE users SET role = "vendor" WHERE id = ?');
    $stmt->execute([$userId]);
    if (isset($_SESSION['user'])) {
        $_SESSION['user']['role'] = 'vendor';
    }
}
