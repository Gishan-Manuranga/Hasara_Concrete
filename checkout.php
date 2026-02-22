<?php
include "header.php";
include "db.php";


if (!isset($_SESSION['user']['id'])) {
    header("Location: login.php?redirect=checkout.php");
    exit();
}
$userId = (int)$_SESSION['user']['id'];


if (!isset($_SESSION['carts'])) $_SESSION['carts'] = [];
if (!isset($_SESSION['carts'][$userId])) $_SESSION['carts'][$userId] = [];
$cart = $_SESSION['carts'][$userId];

$qty = [];
foreach ($cart as $cid) {
    $cid = (int)$cid;
    $qty[$cid] = ($qty[$cid] ?? 0) + 1;
}

$productRows = [];
$subtotal = 0;

if (count($qty) > 0) {
    $ids = array_keys($qty);
    $idsSql = implode(',', array_map('intval', $ids));

    $result = $conn->query("SELECT id,name,price,image_url,description FROM products WHERE id IN ($idsSql)");

    while ($row = $result->fetch_assoc()) {
        $pid = (int)$row['id'];
        $q = $qty[$pid] ?? 1;

        $price = (float)$row['price'];
        $lineTotal = $price * $q;
        $subtotal += $lineTotal;

        $productRows[] = [
            'id' => $pid,
            'name' => $row['name'],
            'description' => $row['description'] ?? '',
            'price' => $price,
            'qty' => $q,
            'lineTotal' => $lineTotal,
            'image_url' => $row['image_url'] ?? ''
        ];
    }
}

$shipping = ($subtotal > 0) ? 0 : 0;
$total = $subtotal + $shipping;
?>

<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h2 class="fw-bold mb-1">Checkout</h2>
      <div class="text-secondary">Review your address, payment and items</div>
    </div>
    <a href="cart.php" class="btn btn-outline-warning">Back to Cart</a>
  </div>

  <?php if (count($productRows) === 0): ?>
    <div class="p-5 rounded-4 border border-secondary bg-black text-center">
      <h4 class="fw-bold mb-2">Your cart is empty</h4>
      <p class="text-secondary mb-4">Add products before checkout.</p>
      <a href="products.php" class="btn btn-primary px-4">Go to Products</a>
    </div>
  <?php else: ?>

  <form action="process_checkout.php" method="POST" class="row g-4">
    <div class="col-lg-8">

      <div class="p-4 rounded-4 border border-secondary bg-black mb-3">
        <h5 class="fw-bold mb-3">1) Shipping Address</h5>

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label text-secondary">Full Name</label>
            <input name="full_name" class="form-control bg-dark text-light border-secondary" required>
          </div>
          <div class="col-md-6">
            <label class="form-label text-secondary">Phone</label>
            <input name="phone" class="form-control bg-dark text-light border-secondary" required>
          </div>

          <div class="col-12">
            <label class="form-label text-secondary">Address Line 1</label>
            <input name="address_line1" class="form-control bg-dark text-light border-secondary" required>
          </div>
          <div class="col-12">
            <label class="form-label text-secondary">Address Line 2 (optional)</label>
            <input name="address_line2" class="form-control bg-dark text-light border-secondary">
          </div>

          <div class="col-md-6">
            <label class="form-label text-secondary">City</label>
            <input name="city" class="form-control bg-dark text-light border-secondary" required>
          </div>
          <div class="col-md-6">
            <label class="form-label text-secondary">Postal Code</label>
            <input name="postal_code" class="form-control bg-dark text-light border-secondary" required>
          </div>
        </div>
      </div>

      <div class="p-4 rounded-4 border border-secondary bg-black mb-3">
        <h5 class="fw-bold mb-3">2) Payment Method</h5>

        <div class="d-flex flex-column gap-3">

          <label class="p-3 rounded-3 border border-secondary bg-dark">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <div class="fw-bold">Card Payment (Dummy)</div>
                <div class="text-secondary small">We will simulate a successful payment.</div>
              </div>
              <input type="radio" name="payment_method" value="CARD" required>
            </div>

            <div class="row g-2 mt-2">
              <div class="col-md-6">
                <input name="card_number" placeholder="Card Number"
                       class="form-control bg-black text-light border-secondary">
              </div>
              <div class="col-md-3">
                <input name="card_exp" placeholder="MM/YY"
                       class="form-control bg-black text-light border-secondary">
              </div>
              <div class="col-md-3">
                <input name="card_cvv" placeholder="CVV"
                       class="form-control bg-black text-light border-secondary">
              </div>
            </div>
          </label>

          <label class="p-3 rounded-3 border border-secondary bg-dark">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <div class="fw-bold">Cash on Delivery</div>
                <div class="text-secondary small">Pay when you receive the items.</div>
              </div>
              <input type="radio" name="payment_method" value="COD" required>
            </div>
          </label>

        </div>
      </div>

      <div class="p-4 rounded-4 border border-secondary bg-black">
        <h5 class="fw-bold mb-3">3) Review Items</h5>

        <?php foreach ($productRows as $p): ?>
          <?php
            $imagePath = "images/" . $p['image_url'];
            $displayImage = (!empty($p['image_url']) && file_exists($imagePath)) ? $imagePath : "images/default-placeholder.jpg";
          ?>
          <div class="d-flex gap-3 align-items-center p-3 rounded-3 border border-secondary bg-dark mb-2">
            <img src="<?php echo $displayImage; ?>" style="width:64px;height:64px;object-fit:cover;border-radius:12px;">
            <div class="flex-grow-1">
              <div class="fw-bold"><?php echo htmlspecialchars($p['name']); ?></div>
              <div class="text-secondary small">Qty: <?php echo (int)$p['qty']; ?></div>
            </div>
            <div class="fw-bold text-warning">LKR <?php echo number_format($p['lineTotal'], 2); ?></div>
          </div>
        <?php endforeach; ?>

      </div>
    </div>

    <div class="col-lg-4">
      <div class="p-4 rounded-4 border border-secondary bg-black position-sticky" style="top: 90px;">
        <h5 class="fw-bold mb-3">Order Summary</h5>

        <div class="d-flex justify-content-between mb-2">
          <span class="text-secondary">Subtotal</span>
          <span class="fw-semibold">LKR <?php echo number_format($subtotal, 2); ?></span>
        </div>

        <div class="d-flex justify-content-between mb-3">
          <span class="text-secondary">Shipping</span>
          <span class="fw-semibold"><?php echo ($shipping == 0) ? "Free" : "LKR " . number_format($shipping, 2); ?></span>
        </div>

        <hr class="border-secondary">

        <div class="d-flex justify-content-between mt-3 mb-3">
          <span class="fw-bold">Total</span>
          <span class="fw-bold text-warning">LKR <?php echo number_format($total, 2); ?></span>
        </div>

        <input type="hidden" name="subtotal" value="<?php echo htmlspecialchars($subtotal); ?>">
        <input type="hidden" name="shipping" value="<?php echo htmlspecialchars($shipping); ?>">
        <input type="hidden" name="total" value="<?php echo htmlspecialchars($total); ?>">

        <button type="submit" class="btn btn-primary w-100 btn-lg">
          Place Order
        </button>

        <div class="text-secondary small mt-3">
          * Invoice will be emailed after successful order.
        </div>
      </div>
    </div>

  </form>

  <?php endif; ?>
</div>

<?php include "footer.php"; ?>
