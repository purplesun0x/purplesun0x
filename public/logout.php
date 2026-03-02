<?php
session_start();
require_once __DIR__ . '/../includes/helpers.php';

session_destroy();
redirect('/public/login.php');
