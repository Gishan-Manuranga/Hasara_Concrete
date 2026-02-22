<?php
session_start();
include "db.php";

if (!isset($_SESSION['user']['id'])) {
    header("Location: login.php?redirect=checkout.php");
    exit();
}
$userId = (int)$_SESSION['user']['id'];

if (!isset($_SESSION['carts'])) $_SESSION['carts'] = [];
if (!isset($_SESSION['carts'][$userId])) $_SESSION['carts'][$userId] = [];
$cart = $_SESSION['carts'][$userId];

if (count($cart) === 0) {
    header("Location: cart.php");
    exit();
}

$full_name = trim($_POST['full_name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$address_line1 = trim($_POST['address_line1'] ?? '');
$address_line2 = trim($_POST['address_line2'] ?? '');
$city = trim($_POST['city'] ?? '');
$postal_code = trim($_POST['postal_code'] ?? '');
$payment_method = $_POST['payment_method'] ?? '';

if ($full_name === '' || $phone === '' || $address_line1 === '' || $city === '' || $postal_code === '') {
    die("Missing shipping details.");
}
if ($payment_method !== 'CARD' && $payment_method !== 'COD') {
    die("Invalid payment method.");
}


$qty = [];
foreach ($cart as $cid) {
    $cid = (int)$cid;
    $qty[$cid] = ($qty[$cid] ?? 0) + 1;
}
$ids = array_keys($qty);
$idsSql = implode(',', array_map('intval', $ids));

$result = $conn->query("SELECT id,name,price FROM products WHERE id IN ($idsSql)");

$items = [];
$calcSubtotal = 0;

while ($row = $result->fetch_assoc()) {
    $pid = (int)$row['id'];
    $q = $qty[$pid] ?? 1;
    $price = (float)$row['price'];
    $line = $price * $q;

    $calcSubtotal += $line;

    $items[] = [
        'product_id' => $pid,
        'name' => $row['name'],
        'unit_price' => $price,
        'qty' => $q,
        'line_total' => $line
    ];
}

$calcShipping = ($calcSubtotal > 0) ? 0 : 0;
$calcTotal = $calcSubtotal + $calcShipping;

$status = ($payment_method === 'CARD') ? 'PAID' : 'COD_PENDING';


$card_last4 = null;
if ($payment_method === 'CARD') {
    $card_number = preg_replace('/\D+/', '', $_POST['card_number'] ?? '');
    $card_last4 = ($card_number !== '') ? substr($card_number, -4) : null;
}

$stmt = $conn->prepare("
  INSERT INTO orders
  (user_id, full_name, phone, address_line1, address_line2, city, postal_code,
   payment_method, card_last4, subtotal, shipping, total, status)
  VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)
");

$stmt->bind_param(
    "issssssssddds",
    $userId, $full_name, $phone, $address_line1, $address_line2, $city, $postal_code,
    $payment_method, $card_last4, $calcSubtotal, $calcShipping, $calcTotal, $status
);

$stmt->execute();
$orderId = $stmt->insert_id;
$stmt->close();


$stmt2 = $conn->prepare("
  INSERT INTO order_items (order_id, product_id, product_name, unit_price, qty, line_total)
  VALUES (?,?,?,?,?,?)
");

foreach ($items as $it) {
    $order_id = $orderId;
    $pid = (int)$it['product_id'];
    $pname = $it['name'];
    $uprice = (float)$it['unit_price'];
    $q = (int)$it['qty'];
    $lt = (float)$it['line_total'];

    $stmt2->bind_param("iisdid", $order_id, $pid, $pname, $uprice, $q, $lt);
    $stmt2->execute();
}
$stmt2->close();
$_SESSION['carts'][$userId] = [];


$userEmail = $_SESSION['user']['email'] ?? null;
if (!$userEmail) {
    $u = $conn->query("SELECT email FROM users WHERE id=$userId");
    if ($u && $urow = $u->fetch_assoc()) $userEmail = $urow['email'];
}


$invoicePath = __DIR__ . "/invoices";
if (!is_dir($invoicePath)) mkdir($invoicePath, 0777, true);

$pdfFile = $invoicePath . "/invoice_order_" . $orderId . ".pdf";

require_once __DIR__ . "/fpdf/fpdf.php";

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetMargins(15, 15, 15);

// ---------- HEADER ----------

$logoPath = __DIR__ . "/images/logo.png";
if (file_exists($logoPath)) {
    $pdf->Image($logoPath, 15, 12, 35); 
}


$pdf->SetFont('Arial','B',28);
$pdf->SetTextColor(220, 0, 0);
$pdf->SetXY(15, 12);
$pdf->Cell(0, 14, "INVOICE", 0, 1, 'R');

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('Arial','',10);
$pdf->Cell(0, 5, "Order ID: #".$orderId, 0, 1, 'R');
$pdf->Cell(0, 5, "Date: ".date("d/m/Y H:i"), 0, 1, 'R');

$pdf->Ln(10);

// ---------- SHIPPING DETAILS ----------
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0, 7, "SHIPPING DETAILS", 0, 1);

$pdf->SetFont('Arial','',10);
$pdf->MultiCell(0, 5,
    $full_name . "\n" .
    $phone . "\n" .
    $address_line1 . "\n" .
    ($address_line2 ? $address_line2 . "\n" : "") .
    $city . " " . $postal_code
);

$pdf->Ln(6);


$pdf->SetFillColor(35, 35, 35);
$pdf->SetTextColor(255,255,255);
$pdf->SetFont('Arial','B',10);

$pdf->Cell(80, 8, "PRODUCT", 1, 0, 'L', true);
$pdf->Cell(20, 8, "QTY",     1, 0, 'C', true);
$pdf->Cell(35, 8, "UNIT",    1, 0, 'R', true);
$pdf->Cell(40, 8, "TOTAL",   1, 1, 'R', true);

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('Arial','',10);

foreach ($items as $it) {
    $pdf->Cell(80, 8, substr($it['name'], 0, 40), 1);
    $pdf->Cell(20, 8, (int)$it['qty'], 1, 0, 'C');
    $pdf->Cell(35, 8, "LKR ".number_format((float)$it['unit_price'], 2), 1, 0, 'R');
    $pdf->Cell(40, 8, "LKR ".number_format((float)$it['line_total'], 2), 1, 1, 'R');
}

$pdf->Ln(7);


$pdf->SetFont('Arial','B',11);
$pdf->Cell(0, 6, "PAYMENT DETAILS", 0, 1);

$pdf->SetFont('Arial','',10);
$pdf->Cell(0, 5, "Payment Method: ".$payment_method, 0, 1);
$pdf->Cell(0, 5, "Payment Status: ".$status, 0, 1);
if (!empty($card_last4)) {
    $pdf->Cell(0, 5, "Card Last 4 Digits: ".$card_last4, 0, 1);
}

$pdf->Ln(6);


$pdf->SetFont('Arial','B',11);

$pdf->Cell(110);
$pdf->Cell(40, 6, "Subtotal", 0, 0, 'L');
$pdf->Cell(0,  6, "LKR ".number_format($calcSubtotal,2), 0, 1, 'R');

$pdf->Cell(110);
$pdf->Cell(40, 6, "Shipping", 0, 0, 'L');
$pdf->Cell(0,  6, ($calcShipping==0 ? "Free" : "LKR ".number_format($calcShipping,2)), 0, 1, 'R');

$pdf->Cell(110);
$pdf->SetTextColor(220,0,0);
$pdf->Cell(40, 8, "TOTAL", 0, 0, 'L');
$pdf->Cell(0,  8, "LKR ".number_format($calcTotal,2), 0, 1, 'R');
$pdf->SetTextColor(0,0,0);

$pdf->Ln(10);


$pdf->SetFont('Arial','B',13);
$pdf->Cell(0, 7, "THANK YOU!", 0, 1);

$pdf->SetFont('Arial','',10);
$pdf->MultiCell(0, 5,
    "Thank you for choosing Hasara Concrete Works.\n".
    "We appreciate your trust and are committed to providing quality products and reliable service."
);

$pdf->Output("F", $pdfFile);


if ($userEmail) {
    require_once __DIR__ . "/PHPMailer/class.phpmailer.php";
    require_once __DIR__ . "/PHPMailer/class.smtp.php";

    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;

    $mail->Username = 'hasaraconcrete@gmail.com';     
    $mail->Password = 'vygx narz ijcm shnz';        
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('hasaraconcrete@gmail.com', 'Hasara Concrete');
    $mail->addAddress($userEmail);

    $mail->Subject = "Invoice for Order #".$orderId;
    $mail->Body = "Hello $full_name,\n\nYour order #$orderId was placed successfully.\nInvoice attached.\n\nThank you,\nHasara Concrete";

    $mail->addAttachment($pdfFile);

    
    $mail->send(); 
}

header("Location: order_success.php?order_id=" . $orderId);
exit();
