
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If user logged in, make cart user-specific
if (isset($_SESSION['user']['id'])) {
    $userId = (int)$_SESSION['user']['id'];

    if (!isset($_SESSION['carts'])) $_SESSION['carts'] = [];
    if (!isset($_SESSION['carts'][$userId])) $_SESSION['carts'][$userId] = [];

    // Make $_SESSION['cart'] always point to THIS user's cart
    $_SESSION['cart'] = &$_SESSION['carts'][$userId];
} else {
    // guest cart (optional)
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
}
?>



<!DOCTYPE html>
<html>
<head>
    <title>Hasara Concrete</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">


    <link href="css/style.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-black border-bottom">
  <div class="container">
    <h5 class="fw-bold">
                    <span class="bg-primary text-dark px-2 py-1 rounded">H<span class="text-white">C</span></span>
                    <a class="navbar-brand fw-bold text-warning" href="index.php">
                    <span class="text-primary">Hasara</span><span class="text-white">Concrete</span>
                    </a>
    </h5>
     

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <ul  class="navbar-nav ms-auto pe-5">
            <li class="nav-item "><a class="nav-link" href="index.php">Home</a></li>
            <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="productsDropdown" role="button" 
        >
        Products
    </a>

    <ul class="dropdown-menu dropdown-menu-dark shadow" aria-labelledby="productsDropdown">
        <li><a class="dropdown-item" href="products.php?name=<?= urlencode('BOX TABLE SEATS'); ?>"> Box table seate</a></li>
        <li><a class="dropdown-item" href="products.php?name=<?= urlencode('DOLAMITE BUDDHA STATUES'); ?>"> Dolamite Budda Statues</a></li>
        <li><a class="dropdown-item" href="products.php?name=<?= urlencode('DUCKS'); ?>">Ducks</a></li>
        <li><a class="dropdown-item" href="products.php?name=<?= urlencode('PAHAN POLES'); ?>">Pahan poles</a></li>
        <li><a class="dropdown-item" href="products.php?name=<?= urlencode('PENGUIN'); ?>">Penguin</a></li>
        <li><a class="dropdown-item" href="products.php?name=<?= urlencode('PONDS (WASANA PONDS)'); ?>">Ponds wasana </a></li>
        <li><a class="dropdown-item" href="products.php?name=<?= urlencode('STAND PONDS'); ?>">Stand ponds</a></li>
        <li><a class="dropdown-item" href="products.php?name=<?= urlencode('CEMENT GANESH STATUES'); ?>">Cement Ganesh Statues</a></li>
        <li><a class="dropdown-item" href="products.php?name=<?= urlencode('DOLOMITE GANESH STATUES'); ?>">Dolomite Ganesh Statues</a></li>
        <li><a class="dropdown-item" href="products.php?name=<?= urlencode('Set of Pahan'); ?>">Set of Pahan</a></li>
        <li><a class="dropdown-item" href="products.php?name=<?= urlencode('WATERFALL BUDDHA &  GANESH POLES'); ?>">Cement Table Set</a></li>
        <li><a class="dropdown-item" href="products.php?name=<?= urlencode('CEMENT TOMBSTONES'); ?>">Cement Tombstone</a></li>
        <li><a class="dropdown-item" href="products.php?name=<?= urlencode('FENCE POTS'); ?>">Fence Pots</a></li>
        <li><a class="dropdown-item" href="products.php?name=<?= urlencode('Y Poles'); ?>">Y Poles</a></li>
    </ul>
</li>
<li>
    <a class="nav-link" href="my_orders.php" title="My Orders">
  <i class="fa-solid fa-bag-shopping"></i>
</a>

</li>

            <li class="nav-item "><a class="nav-link" href="about.php">About US</a></li>
            <li class="nav-item "><a class="nav-link" href="contact.php">Contact US</a></li>

        </ul>


         <?php
          $cart_count = 0;
          if(isset($_SESSION['cart'])){
              $cart_count = count($_SESSION['cart']);
          } 
        ?> 




        <?php if(isset($_SESSION['user'])): ?>
            <li class="nav-item pe-5"><a class="nav-link position-relative  " href="cart.php">
               <i class="bi bi-cart3 fs-5"></i>

              <?php if($cart_count > 0): ?>
              <span class="position-absolute top-20 start-80 translate-middle badge rounded-pill bg-primary text-dark">
              <?= $cart_count; ?>
            </span>
            <?php endif; ?>
          </a>
          </li>
            <li class="nav-item"><a class="nav-link btn bg-danger" href="logout.php">Logout</a></li>
        <?php else: ?>
            <li class="nav-item"><a class="nav-link btn btn-primary text-white ms-2" href="login.php">Login</a></li>
            <li class="nav-item"><a class="nav-link btn bg-primary ms-2 text-white" href="register.php">Register</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>


</body>
</html>