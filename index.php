<?php include "header.php"; ?>
<link rel="stylesheet" href="CSS/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<?php include "db.php"; ?>

<?php
$result = $conn->query("SELECT * FROM products");
?>

<body>
    

<section class="hero-section d-flex align-items-center ">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-4 fw-bold">
                    Build with <span class="text-primary">Confidence</span>
                </h1>
                <p class="lead">
                    Professional-grade concrete materials trusted by builders worldwide.
                </p>

                <!-- Search Bar -->
                <div class="mt-4">
                    <form action="products.php" method="GET" class="d-flex justify-content-start">
                        <input type="text" name="search" 
                            class="form-control form-control-lg w-75 me-2" 
                            placeholder="Search cement products..."
                            required>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </form>
                </div>

            </div>

            <div class="col-md-6 p-3 mt-5 mb-5 text-center">
                <img src="images/cement 2.jpg"
                     class="img-fluid rounded shadow-lg">
            </div>
        </div>
    </div>
</section>
<section style="background-color: var(--bg-custom-color);" class="p-5">


<div class="container">
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="fw-bold">Featured <span class="text-primary border-bottom border-3 border-primary">Selection</span></h2>
            <p class="text-secondary">Handpicked premium materials trusted by industry professionals</p>
        </div>
    </div>

<div class="featured-slider position-relative">

    <!-- Left Arrow -->
    <button class="slider-btn slider-btn-left" onclick="moveSlide(-1)">
        <i class="fa-solid fa-chevron-left"></i>
    </button>

    <div class="slider-viewport">
        <div class="row g-4 flex-nowrap slider-row" id="sliderRow">

            <?php while($row = $result->fetch_assoc()): ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 slider-item">
                <div style="background-color: var(--bs-custom-color);" class="card h-100 custom-card text-white">
                    <div class="position-relative">
                        <?php 
                            $imagePath = "images/" . $row['image_url']; 
                            $displayImage = (!empty($row['image_url']) && file_exists($imagePath)) 
                                            ? $imagePath 
                                            : "images/default-placeholder.jpg";
                        ?>
                        <img src="<?php echo $displayImage; ?>" class="card-img-top rounded-top" alt="Product">
                        
                    </div>
                    
     

                    <div class="card-body d-flex flex-column">
                        <h4 class="card-title fw-bold mb-3"><?php echo $row['name']; ?></h4>


                        <h5 class="fw-bold text-blue mb-4">LKR <?php echo number_format($row['price']); ?></h5>
                        <!-- <h6 class="text-secondary text-justify mb-4"><?php echo $row['description'] ?></h6> -->

                        <div class="mt-auto">
                            <form action="add_to_cart.php" method="POST">
                                <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-primary-custom bg-primary w-100 mb-2 py-2">
                                    <i class="fa-solid fa-cart-shopping me-2"></i> Add to Cart
                                </button>
                            </form>

                            <a class="btn btn-outline-primary w-100 btn-sm" href="product_details.php?id=<?php echo $row['id']; ?>">
                                View Details
                            </a>

                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>

        </div>
    </div>

    <!-- Right Arrow -->
    <button class="slider-btn slider-btn-right" onclick="moveSlide(1)">
        <i class="fa-solid fa-chevron-right"></i>
    </button>

</div>



</div>

</section>
</body>

<?php include "footer.php"; ?>