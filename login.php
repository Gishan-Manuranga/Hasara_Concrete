<?php include "header.php"; ?>
<link rel="stylesheet" href="CSS/style.css">
<?php include "db.php"; ?>

<?php
if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email='$email'");
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {

    $_SESSION['user'] = $user;
    $_SESSION['user_id'] = $user['id'];   // ✅ ADD THIS LINE

    $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : "index.php";
    header("Location: " . $redirect);
    exit();
 }
 else {
        echo "<div class='alert alert-danger'>Invalid Credentials</div>";
    }
}
?>

<div class="container py-5">
  <div class="row justify-content-center align-items-center">
    <div class="col-12 col-lg-10">
      <div class="login-wrap shadow-lg">

        <!-- Left Image/Brand Panel -->
        <div class="login-left d-none d-md-flex">
          <div class="p-4 p-lg-5 w-100">
            <div class="d-flex align-items-center gap-2 mb-4">
            <h5 class="fw-bold">
              <span class="bg-primary text-dark px-2 py-1 rounded">H<span class="text-white">C</span></span>
                
              <span class="text-primary">Hasara</span><span class="text-white">Concrete</span>
            </h5>
            </div>

            <h2 class="fw-bold mb-3">Build stronger. Shop smarter.</h2>
            <p class="text-light-50 mb-4">
              Sign in to manage your cart, view product details, and place orders quickly.
            </p>

            <div class="feature-list">
              <div class="feature-item">
                <span class="dot"></span>
                <span>Fast checkout & saved cart</span>
              </div>
              <div class="feature-item">
                <span class="dot"></span>
                <span>Premium cement & concrete products</span>
              </div>
              <div class="feature-item">
                <span class="dot"></span>
                <span>Trusted quality for your projects</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Form Panel -->
        <div class="login-right">
          <div class="login-card">
            <h3 class="fw-bold mb-1">Welcome back</h3>
            <p class="text-secondary mb-4">Sign in to continue with Hasara Concrete</p>

            <form method="POST">
              <div class="mb-3">
                <label class="form-label text-secondary">Email</label>
                <input type="email" name="email" class="form-control form-control-lg modern-input"
                       placeholder="Enter your email" required>
              </div>

              <div class="mb-3">
                <label class="form-label text-secondary">Password</label>
                <input type="password" name="password" class="form-control form-control-lg modern-input"
                       placeholder="Enter your password" required>
              </div>

              <button name="login" class="btn btn-primary w-100 btn-lg modern-btn">
                Sign In
              </button>

              <div class="text-center mt-3">
                <span class="text-secondary">Don’t have an account?</span>
                <a href="register.php" class="ms-1 link-accent">Register</a>
              </div>
            </form>

          </div>
        </div>

      </div>
    </div>
  </div>
</div>


<?php include "footer.php"; ?>
