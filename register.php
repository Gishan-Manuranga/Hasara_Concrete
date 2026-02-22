<?php include "header.php"; ?>
<?php include "db.php"; ?>

<?php
if(isset($_POST['register'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $conn->query("INSERT INTO users (name,email,password)
                  VALUES ('$name','$email','$password')");

    header("Location: login.php");
}
?>

<div class="container py-5">
  <div class="row justify-content-center align-items-center">
    <div class="col-12 col-lg-10">
      <div class="login-wrap shadow-lg">

        <!-- Left Branding Panel -->
        <div class="login-left d-none d-md-flex">
          <div class="p-4 p-lg-5 w-100">
            <div class="d-flex align-items-center gap-2 mb-4">
            <h5 class="fw-bold">
              <span class="bg-primary text-dark px-2 py-1 rounded">H<span class="text-white">C</span></span>
              <span class="text-primary">Hasara</span><span class="text-white">Concrete</span>
            </h5>
            </div>

            <h2 class="fw-bold mb-3">Start building with confidence</h2>
            <p class="text-light-50 mb-4">
              Create your account to explore premium cement and concrete products.
            </p>

            <div class="feature-list">
              <div class="feature-item">
                <span class="dot"></span>
                <span>Access exclusive product deals</span>
              </div>
              <div class="feature-item">
                <span class="dot"></span>
                <span>Save items to your cart</span>
              </div>
              <div class="feature-item">
                <span class="dot"></span>
                <span>Fast & secure checkout</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Register Form -->
        <div class="login-right">
          <div class="login-card">
            <h3 class="fw-bold mb-1">Create Account</h3>
            <p class="text-secondary mb-4">Join Hasara Concrete today</p>

            <form method="POST">

              <div class="mb-3">
                <label class="form-label text-secondary">Full Name</label>
                <input type="text" name="name"
                       class="form-control form-control-lg modern-input"
                       placeholder="Enter your full name" required>
              </div>

              <div class="mb-3">
                <label class="form-label text-secondary">Email</label>
                <input type="email" name="email"
                       class="form-control form-control-lg modern-input"
                       placeholder="Enter your email" required>
              </div>

              <div class="mb-3">
                <label class="form-label text-secondary">Password</label>
                <input type="password" name="password"
                       class="form-control form-control-lg modern-input"
                       placeholder="Create a strong password" required>
              </div>

              <button name="register" class="btn btn-primary w-100 btn-lg modern-btn">
                Create Account
              </button>

              <div class="text-center mt-3">
                <span class="text-secondary">Already have an account?</span>
                <a href="login.php" class="ms-1 link-accent">Sign In</a>
              </div>

            </form>

          </div>
        </div>

      </div>
    </div>
  </div>
</div>


<?php include "footer.php"; ?>
