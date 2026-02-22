<?php
include "header.php";
include "db.php";

if (!isset($_SESSION['user']['id'])) {
    header("Location: login.php?redirect=my_orders.php");
    exit();
}
$userId = (int)$_SESSION['user']['id'];


$stmt = $conn->prepare("SELECT id, total, status, payment_method, created_at, cancelled_at FROM orders WHERE user_id=? ORDER BY id DESC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$res = $stmt->get_result();
$stmt->close();

function canCancel($createdAt, $status) {
    if ($status === 'CANCELLED') return false;
    $createdTs = strtotime($createdAt);
    if (!$createdTs) return false;
    return (time() - $createdTs) <= (2 * 60 * 60); // 2 hours
}
?>

<div class="container py-5">
  <div class="d-flex align-items-center justify-content-between mb-4">
    <div>
      <h2 class="fw-bold mb-1">My Orders</h2>
      <div class="text-secondary">You can cancel an order within 2 hours after placing it.</div>
    </div>
    <a href="products.php" class="btn btn-outline-warning">Continue Shopping</a>
  </div>

  <?php if (isset($_GET['msg'])): ?>
    <script>
      alert("<?php echo htmlspecialchars($_GET['msg']); ?>");
      window.history.replaceState({}, document.title, "my_orders.php");
    </script>
  <?php endif; ?>

  <?php if ($res->num_rows === 0): ?>
    <div class="p-5 rounded-4 border border-secondary bg-black text-center">
      <h4 class="fw-bold mb-2">No orders found</h4>
      <p class="text-secondary mb-4">You haven't placed any orders yet.</p>
      <a href="products.php" class="btn btn-primary px-4">Go to Products</a>
    </div>
  <?php else: ?>

    <div class="list-group">
      <?php while ($o = $res->fetch_assoc()): ?>
        <?php
          $orderId = (int)$o['id'];
          $status = $o['status'];
          $createdAt = $o['created_at'];
          $cancelAllowed = canCancel($createdAt, $status);
        ?>
        <div class="list-group-item bg-black text-light border-secondary rounded-4 mb-3 p-4">
          <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
              <div class="fw-bold fs-5">Order #<?php echo $orderId; ?></div>
              <div class="text-secondary small">Placed: <?php echo htmlspecialchars($createdAt); ?></div>
              <div class="text-secondary small">Payment: <?php echo htmlspecialchars($o['payment_method']); ?></div>
            </div>

            <div class="text-end">
              <div class="fw-bold text-warning">LKR <?php echo number_format((float)$o['total'], 2); ?></div>
              <div class="badge bg-secondary mt-1"><?php echo htmlspecialchars($status); ?></div>
            </div>
          </div>

          <hr class="border-secondary my-3">

          <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">


            <?php if ($cancelAllowed): ?>
              <form method="POST" action="cancel_order.php" class="d-flex gap-2 flex-wrap m-0">
                <input type="hidden" name="order_id" value="<?php echo $orderId; ?>">
                <input type="text" name="reason" class="form-control form-control-sm bg-dark text-light border-secondary"
                       placeholder="Reason (optional)" style="max-width:280px;">
                <button type="submit" class="btn btn-danger btn-sm"
                        onclick="return confirm('Cancel Order #<?php echo $orderId; ?> ?');">
                  Cancel (within 2 hours)
                </button>
              </form>
            <?php else: ?>
              <div class="text-secondary small">
                Cancel not available (only within 2 hours).
              </div>
            <?php endif; ?>
          </div>
        </div>
      <?php endwhile; ?>
    </div>

  <?php endif; ?>
</div>

<?php include "footer.php"; ?>