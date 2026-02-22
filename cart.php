<?php include "header.php"; ?>
<?php include "db.php"; ?>

<?php

if (isset($_POST['product_id'])) {
    $id = (int)$_POST['product_id'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $_SESSION['cart'][] = $id;

    header("Location: cart.php");
    exit();
}


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'remove' && isset($_POST['id'])) {
        $removeId = (int)$_POST['id'];

        
        $_SESSION['cart'] = array_values(array_filter($_SESSION['cart'], function ($x) use ($removeId) {
            return (int)$x !== $removeId;
        }));

        header("Location: cart.php");
        exit();
    }

    if ($action === 'clear') {
        $_SESSION['cart'] = [];
        header("Location: cart.php");
        exit();
    }

    if (($action === 'qty_inc' || $action === 'qty_dec') && isset($_POST['id'])) {
        $pid = (int)$_POST['id'];

      
        $qtyMap = [];
        foreach ($_SESSION['cart'] as $cid) {
            $cid = (int)$cid;
            $qtyMap[$cid] = ($qtyMap[$cid] ?? 0) + 1;
        }

        $currentQty = $qtyMap[$pid] ?? 0;

        if ($action === 'qty_inc') {
            $_SESSION['cart'][] = $pid;
        } else {
           
            if ($currentQty > 1) {
                $removed = false;
                $newCart = [];
                foreach ($_SESSION['cart'] as $cid) {
                    $cid = (int)$cid;
                    if (!$removed && $cid === $pid) {
                        $removed = true; 
                        continue;
                    }
                    $newCart[] = $cid;
                }
                $_SESSION['cart'] = $newCart;
            } elseif ($currentQty === 1) {
               
                $_SESSION['cart'] = array_values(array_filter($_SESSION['cart'], fn($x) => (int)$x !== $pid));
            }
        }

        header("Location: cart.php");
        exit();
    }
}

// 4) Build quantity map from session cart (AliExpress style)
$qty = [];
foreach ($_SESSION['cart'] as $cid) {
    $cid = (int)$cid;
    $qty[$cid] = ($qty[$cid] ?? 0) + 1;
}

$productRows = [];
$subtotal = 0;

if (count($qty) > 0) {
    $ids = array_keys($qty);
    $idsSql = implode(',', array_map('intval', $ids));

    // Fetch all products in one query
    $result = $conn->query("SELECT * FROM products WHERE id IN ($idsSql)");

    while ($row = $result->fetch_assoc()) {
        $pid = (int)$row['id'];
        $q = $qty[$pid] ?? 1;

        $price = (float)$row['price'];
        $lineTotal = $price * $q;
        $subtotal += $lineTotal;

        $productRows[] = [
            'id' => $pid,
            'name' => $row['name'],
            'category' => $row['category'] ?? '',
            'price' => $price,
            'qty' => $q,
            'lineTotal' => $lineTotal,
            'image_url' => $row['image_url'] ?? ''
        ];
    }

    // Optional: sort products to show in same order as cart ids
    usort($productRows, function($a, $b) use ($ids) {
        return array_search($a['id'], $ids) <=> array_search($b['id'], $ids);
    });
}

// Summary calculations (customize as you want)
$shipping = ($subtotal > 0) ? 0 : 0; // free shipping example
$grandTotal = $subtotal + $shipping;
?>

<div class="container py-5">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold mb-1">Shopping Cart</h2>
            <div class="text-secondary">
                <?php echo count($productRows); ?> item(s)
            </div>
        </div>

        <?php if (count($productRows) > 0): ?>
        <form method="POST" class="m-0">
            <input type="hidden" name="action" value="clear">
            <button class="btn btn-outline-danger btn-sm">
                <i class="fa-solid fa-trash me-2"></i> Clear Cart
            </button>
        </form>
        <?php endif; ?>
    </div>

    <?php if (count($productRows) === 0): ?>
        <div class="cart-empty p-5 rounded-4 border border-secondary bg-black text-center">
            <h4 class="fw-bold mb-2">Your cart is empty</h4>
            <p class="text-secondary mb-4">Browse products and add items to your cart.</p>
            <a href="products.php" class="btn btn-primary px-4">Go to Products</a>
        </div>
    <?php else: ?>

    <div class="row g-4">
        <!-- LEFT: Cart Items -->
        <div class="col-lg-8">
            <?php foreach ($productRows as $p): ?>
                <?php
                    $imagePath = "images/" . $p['image_url'];
                    $displayImage = (!empty($p['image_url']) && file_exists($imagePath))
                        ? $imagePath
                        : "images/default-placeholder.jpg";
                ?>

<div class="cart-item-card p-3 p-md-4 rounded-4 border border-secondary bg-black mb-3">
  <div class="cart-row">

    <!-- Fixed Image -->
    <div class="cart-img-box">
      <img src="<?php echo $displayImage; ?>" class="cart-img-fixed" alt="Product">
    </div>

    <!-- Details -->
    <div class="cart-details">
      <div class="fw-bold cart-title">
        <?php echo htmlspecialchars($p['name']); ?>
      </div>

      <div class="text-secondary small cart-desc">
        <?php echo !empty($product['description'])
          ? htmlspecialchars($product['description'])
          : "High quality concrete product for your projects."; ?>
      </div>

      <div class="d-flex flex-wrap align-items-center gap-3 mt-3">

        <!-- Quantity -->
        <div class="qty-box">
          <form method="POST" class="m-0">
            <input type="hidden" name="action" value="qty_dec">
            <input type="hidden" name="id" value="<?php echo (int)$p['id']; ?>">
            <button class="qty-btn" type="submit">−</button>
          </form>

          <div class="qty-value"><?php echo (int)$p['qty']; ?></div>

          <form method="POST" class="m-0">
            <input type="hidden" name="action" value="qty_inc">
            <input type="hidden" name="id" value="<?php echo (int)$p['id']; ?>">
            <button class="qty-btn" type="submit">+</button>
          </form>
        </div>

        <!-- Subtotal -->
        <div class="cart-subtotal">
          <div class="text-secondary small">Subtotal</div>
          <div class="fw-bold text-warning">
            LKR <?php echo number_format($p['lineTotal'], 2); ?>
          </div>
        </div>

        <!-- Remove -->
        <form method="POST" class="m-0">
          <input type="hidden" name="action" value="remove">
          <input type="hidden" name="id" value="<?php echo (int)$p['id']; ?>">
          <button class="btn btn-outline-danger btn-sm cart-remove-btn">
            <i class="fa-solid fa-trash me-2"></i> Remove
          </button>
        </form>

      </div>
    </div>

  </div>
</div>


            <?php endforeach; ?>
        </div>

        <!-- RIGHT: Summary -->
        <div class="col-lg-4">
            <div class="summary-card p-4 rounded-4 border border-secondary bg-black">
                <h5 class="fw-bold mb-3">Order Summary</h5>

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-secondary">Items Subtotal</span>
                    <span class="fw-semibold">LKR <?php echo number_format($subtotal, 2); ?></span>
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <span class="text-secondary">Shipping</span>
                    <span class="fw-semibold">
                        <?php echo ($shipping == 0) ? "Free" : "$" . number_format($shipping, 2); ?>
                    </span>
                </div>

                <hr class="border-secondary">

                <div class="d-flex justify-content-between mt-3 mb-4">
                    <span class="fw-bold">Total</span>
                    <span class="fw-bold text-warning">LKR <?php echo number_format($grandTotal, 2); ?></span>
                </div>

                <a href="checkout.php" class="btn btn-primary w-100 btn-lg">
                    Proceed to Checkout
                </a>

                <a href="products.php" class="btn btn-outline-warning w-100 mt-3">
                    Continue Shopping
                </a>

                <div class="text-secondary small mt-3">
                    * You can change quantities using + and - buttons.
                </div>
            </div>
        </div>
    </div>

    <?php endif; ?>
</div>




<?php include "footer.php"; ?>
