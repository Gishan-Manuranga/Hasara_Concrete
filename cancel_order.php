<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['user']['id'])) {
    header("Location: login.php?redirect=my_orders.php");
    exit();
}
$userId = (int)$_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: my_orders.php?msg=" . urlencode("Invalid request."));
    exit();
}

$orderId = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;
$reason = trim($_POST['reason'] ?? '');

if ($orderId <= 0) {
    header("Location: my_orders.php?msg=" . urlencode("Invalid order."));
    exit();
}


$stmt = $conn->prepare("SELECT id, user_id, total, status, created_at, payment_method FROM orders WHERE id=? LIMIT 1");
$stmt->bind_param("i", $orderId);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$order || (int)$order['user_id'] !== $userId) {
    header("Location: my_orders.php?msg=" . urlencode("Order not found."));
    exit();
}

if ($order['status'] === 'CANCELLED') {
    header("Location: my_orders.php?msg=" . urlencode("Order already cancelled."));
    exit();
}


$createdTs = strtotime($order['created_at']);
if (!$createdTs) {
    header("Location: my_orders.php?msg=" . urlencode("Cannot read order time."));
    exit();
}

if ((time() - $createdTs) > (2 * 60 * 60)) {
    header("Location: my_orders.php?msg=" . urlencode("Cancel time expired. Only within 2 hours."));
    exit();
}


$stmt = $conn->prepare("UPDATE orders SET status='CANCELLED', cancelled_at=NOW(), cancel_reason=? WHERE id=? AND user_id=?");
$stmt->bind_param("sii", $reason, $orderId, $userId);
$stmt->execute();
$stmt->close();

$ownerEmail = "hasaraconcrete@gmail.com"; 
$fromGmail   = "hasaraconcrete@gmail.com"; 
$appPassword = "vygx narz ijcm shnz";    


$customerName = $_SESSION['user']['name'] ?? 'Customer';
$customerEmail = $_SESSION['user']['email'] ?? '';


require_once __DIR__ . "/PHPMailer/class.phpmailer.php";
require_once __DIR__ . "/PHPMailer/class.smtp.php";

$mail = new PHPMailer();
$mail->isSMTP();
$mail->Host = "smtp.gmail.com";
$mail->SMTPAuth = true;
$mail->Username = $fromGmail;
$mail->Password = $appPassword;
$mail->SMTPSecure = "tls";
$mail->Port = 587;


$mail->SMTPOptions = array(
  'ssl' => array(
    'verify_peer' => false,
    'verify_peer_name' => false,
    'allow_self_signed' => true
  )
);

$mail->setFrom($fromGmail, "Hasara Concrete Website");
$mail->addAddress($ownerEmail);

$mail->Subject = "Order Cancelled: Order #".$orderId;
$mail->Body =
    "An order has been cancelled.\n\n" .
    "Order ID: #$orderId\n" .
    "Customer ID: $userId\n" .
    "Customer Name: $customerName\n" .
    "Customer Email: $customerEmail\n" .
    "Payment Method: ".$order['payment_method']."\n" .
    "Total: LKR ".number_format((float)$order['total'], 2)."\n" .
    "Placed At: ".$order['created_at']."\n\n" .
    "Reason: ".($reason !== '' ? $reason : "N/A")."\n";

$mail->send(); 

header("Location: my_orders.php?msg=" . urlencode("Order cancelled successfully."));
exit();