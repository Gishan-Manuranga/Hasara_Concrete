<?php include "header.php"; ?>
<link href="css/style.css" rel="stylesheet">
<?php include "db.php"; ?>

<div class="container py-5">
    <h2 class="mb-4">Our Products</h2>
    <div class="row">

<?php
if(isset($_GET['name']) && $_GET['name'] != ""){

    $name = $conn->real_escape_string($_GET['name']);
    $result = $conn->query("
        SELECT * FROM products 
        WHERE name = '$name'
        LIMIT 1
    ");

}
elseif(isset($_GET['search']) && $_GET['search'] != ""){

    $search = $conn->real_escape_string($_GET['search']);
    $result = $conn->query("
        SELECT * FROM products 
        WHERE name LIKE '%$search%' 
    ");

}
else{

    $result = $conn->query("SELECT * FROM products");

}


while($row = $result->fetch_assoc()):
?> 


<div class="col-md-4 mb-4">
    <div style="background-color: var(--bg-custom-color);" class="card  text-light card h-100 custom-card ">
        <div class="position-relative h-300 w-300">
                        <?php 
                            $imagePath = "images/" . $row['image_url']; 
                            $displayImage = (!empty($row['image_url']) && file_exists($imagePath)) 
                                            ? $imagePath 
                                            : "images/default-placeholder.jpg";
                        ?>
                        <img src="<?php echo $displayImage; ?>" class="card-img-top rounded-top" alt="Product">
                        
                    </div>
        <div class="card-body">
            <h5 class=" text"><?= $row['name']; ?></h5>
            <!-- <p class=" text-light"><?= $row['description']; ?></p> -->
            <h6 class="text-warning">Rs<?= $row['price']; ?></h6>

            <form method="POST" action="cart.php">
                <input type="hidden" name="product_id" value="<?= $row['id']; ?>">
                <div class="mt-auto">
                            <form action="add_to_cart.php" method="POST">
                                <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-primary-custom bg-primary w-100 mb-2 py-2">
                                    <i class="fa-solid fa-cart-shopping me-2"></i> Add to Cart
                                </button>
                            </form>
                            <a class="btn btn-outline-primary w-100 mb-2 py-2" href="product_details.php?id=<?php echo $row['id']; ?>">
                                View Details
                            </a>

                        </div>
            </form>
        </div>
    </div>
</div>


<?php endwhile; ?>

    </div>
</div>

<?php include "footer.php"; ?>
