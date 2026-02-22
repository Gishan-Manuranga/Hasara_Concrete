<?php
session_start();
require_once "require_login.php";
require_once "db.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: products.php");
    exit();
}


$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    header("Location: products.php");
    exit();
}

include "header.php";
?>



<div class="container py-5">
    <div class="row g-4">
        <div class="col-md-6">
            <?php
            $imagePath = "images/" . $product['image_url'];
            $displayImage = (!empty($product['image_url']) && file_exists($imagePath)) ? $imagePath : "images/default-placeholder.jpg";
            ?>
            <img src="<?php echo $displayImage; ?>" class="img-fluid rounded" alt="Product">
        </div>

        <div class="col-md-6">
            <h2 class="fw-bold"><?php echo htmlspecialchars($product['name']); ?></h2>
            <p class="text-secondary"><?php echo htmlspecialchars($product['description']); ?></p>

            <h3 class="text-primary fw-bold mb-3">
                LKR <?php echo number_format($product['price'], 2); ?>
            </h3>


            <!-- Add to Cart -->
            <form action="add_to_cart.php" method="POST" class="mt-5">
                <input type="hidden" name="product_id" value="<?php echo (int)$product['id']; ?>">
                <button type="submit" class="btn btn-primary btn-lg w-100">
                    <i class="fa-solid fa-cart-shopping me-2"></i> Add to Cart
                </button>
            </form>

            <!-- Buy Now -->
            <form action="add_to_cart.php" method="POST" class="mt-4">
                <input type="hidden" name="product_id" value="<?php echo (int)$product['id']; ?>">
                <input type="hidden" name="buy_now" value="1">
                <button type="submit" class="btn btn-outline-primary btn-lg w-100">
                    <i class="fa-solid fa-bolt me-2"></i> Buy Now
                </button>
            </form>
               

        </div>
    </div>
</div>

<?php include "footer.php"; ?>
