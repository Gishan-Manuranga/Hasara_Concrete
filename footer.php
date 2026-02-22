<html>
  <head>
  </head>
<footer class="bg-black text-light pt-5 pb-3 mt-5 border-top">
    <div class="container">
        <div class="row">

            
            <div class="col-md-3 mb-4">
                <h5 class="fw-bold">
                    <span class="bg-primary text-dark px-2 py-1 rounded">H<span class="text-white">C</span></span>
                    <span class="text-primary">Hasara</span><span class="text-white">Concrete</span>
                </h5>
                <p class="text-secondary mt-3">
                    Premium construction materials for your building projects.
                </p>
            </div>

            <div class="col-md-3 mb-4">
                <h6 class="fw-bold mb-3">Quick Links</h6>
                <ul class="list-unstyled">
                    <li><a href="index.php" class="text-secondary text-decoration-none d-block mb-2">Home</a></li>
                    <li><a href="products.php" class="text-secondary text-decoration-none d-block mb-2">Products</a></li>
                    <li><a href="about.php" class="text-secondary text-decoration-none d-block mb-2">About Us</a></li>
                    <li><a href="contact.php" class="text-secondary text-decoration-none d-block">Contact</a></li>
                </ul>
            </div>

           
            <div class="col-md-3 mb-4">
                <h6 class="fw-bold mb-3">Categories</h6>
                <ul class="list-unstyled">
                    <li class="text-secondary mb-2">Cement</li>
                    <li class="text-secondary mb-2">Concrete Blocks</li>
                    <li class="text-secondary mb-2">Bricks</li>
                    <li class="text-secondary">Paving Stones</li>
                </ul>
            </div>

            <div class="col-md-3 mb-4">
                <h6 class="fw-bold mb-3">Contact Us</h6>
                <p class="text-secondary mb-3">Tel: 027 2224053</p>
                <p class="text-secondary mb-3">Phone: 071 5323980</p>
                <p class="text-secondary mb-2">Email: hasaraconcrete@email.com</p>

               
                <div>
                    <a href="https://www.facebook.com/share/1EwEigaTyS/?mibextid=wwXIfr" class="text-secondary me-3 fs-5">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="https://wa.me/94711672676" class="text-secondary me-3 fs-5">
                        <i class="bi bi-whatsapp"></i>
                    </a>
                    <a href="mailto:hasaraconcrete@email.com" class="text-secondary me-3 fs-5">
                        <i class="bi bi-envelope"></i>
                    </a>
                </div>
            </div>

        </div>

        
        <hr class="border-secondary">

        
        <div class="text-center text-secondary">
            © 2026 Hasara Concrete. All rights reserved.
        </div>
    </div>
</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
let currentPosition = 0;

function moveSlide(direction) {
  const slider = document.getElementById("sliderRow");
  const items = document.querySelectorAll(".slider-item");
  if (!items.length) return;

 
  let step = items[0].offsetWidth;
  if (items.length > 1) {
    step = items[1].offsetLeft - items[0].offsetLeft;
  }

  const viewport = slider.parentElement;
  const maxPosition = Math.max(0, slider.scrollWidth - viewport.clientWidth);

  currentPosition += direction * step;
  currentPosition = Math.max(0, Math.min(currentPosition, maxPosition));

  slider.style.transform = `translate3d(-${currentPosition}px,0,0)`;
}
</script>





</body>
</html>
