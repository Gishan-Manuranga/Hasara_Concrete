<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
    $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "products.php";
    header("Location: login.php?redirect=" . urlencode($redirect));
    exit();
}


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: products.php");
    exit();
}

$productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
if ($productId <= 0) {
    header("Location: products.php");
    exit();
}

$userId = (int)$_SESSION['user']['id'];

// Insert or update quantity
$stmt = $conn->prepare("SELECT quantity FROM cart_items WHERE user_id=? AND product_id=?");
$stmt->bind_param("ii", $userId, $productId);
$stmt->execute();
$res = $stmt->get_result();
$stmt->close();

if ($res && $res->num_rows > 0) {
    $stmt = $conn->prepare("UPDATE cart_items SET quantity = quantity + 1 WHERE user_id=? AND product_id=?");
    $stmt->bind_param("ii", $userId, $productId);
    $stmt->execute();
    $stmt->close();
} else {
    $stmt = $conn->prepare("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, 1)");
    $stmt->bind_param("ii", $userId, $productId);
    $stmt->execute();
    $stmt->close();
}

// Redirect (Buy Now -> checkout, else cart)
if (isset($_POST['buy_now']) && $_POST['buy_now'] == "1") {
    header("Location: checkout.php");
} else {
    header("Location: cart.php");
}
exit();