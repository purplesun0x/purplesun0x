<?php
require __DIR__ . '/init.php';

session_destroy();
session_start();
$_SESSION['success'] = 'You have been logged out.';
redirectTo('/login.php');
