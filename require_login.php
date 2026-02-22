<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    
    $currentUrl = $_SERVER['REQUEST_URI']; 
    header("Location: login.php?redirect=" . urlencode($currentUrl));
    exit();
}
