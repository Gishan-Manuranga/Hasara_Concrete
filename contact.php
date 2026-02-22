
<?php include "header.php"; ?>

<head>
    <link href="css/style.css" rel="stylesheet">
</head>

<div class="container py-5">
  <div class="row g-4 align-items-stretch">

    <div class="col-lg-6">
      <div class="p-4 p-lg-5 rounded-4 border border-secondary bg-black h-100">
        <h2 class="fw-bold mb-2">Contact Us</h2>
        <p class="text-secondary mb-4">Hasara Concrete Works — We’re here to help with your orders and inquiries.</p>

        <div class="d-flex gap-3 mb-3">
          <div class="icon-pill">☎️</div>
          <div>
            <div class="text-secondary small">TELE PHONE</div>
            <div class="fw-semibold">027 2224053</div>
          </div>
        </div>

        <div class="d-flex gap-3 mb-3">
          <div class="icon-pill">📞</div>
          <div>
            <div class="text-secondary small">MOBILE PHONE</div>
            <div class="fw-semibold">071 5323980</div>
          </div>
        </div>

        <div class="d-flex gap-3 mb-3">
          <div class="icon-pill">💬</div>
          <div>
            <div class="text-secondary small">WHATSAPP</div>
            <div class="fw-semibold">071 1672676</div>
          </div>
        </div>

        <div class="d-flex gap-3 mb-3">
          <div class="icon-pill">📍</div>
          <div>
            <div class="text-secondary small">LOCATION</div>
            <div class="fw-semibold">24 Mile post, Bendiwewa, Sri Lanka</div>
          </div>
        </div>

        <div class="d-flex gap-3">
          <div class="icon-pill">📧</div>
          <div>
            <div class="text-secondary small">E-MAIL</div>
            <div class="fw-semibold">hasaraconcrete@email.com</div>
          </div>
        </div>

        <hr class="border-secondary my-4">

        <div class="d-flex flex-wrap gap-2">
          <a class="btn btn-primary" href="tel:0272224053">Call Telephone</a>
          <a class="btn btn-outline-warning" href="tel:0715323980">Call Phone</a>
          <a class="btn btn-outline-success" href="https://wa.me/94711672676" target="_blank">WhatsApp</a>
          <a class="btn btn-outline-light" href="mailto:hasaraconcrete@email.com">Email</a>
        </div>
      </div>
    </div>

    <div class="col-lg-6">
      <div class="p-4 p-lg-5 rounded-4 border border-secondary bg-black h-100">
        <h4 class="fw-bold mb-3">Send us a message</h4>
        <p class="text-secondary">Fill this form and we will contact you soon.</p>

        <!-- ✅ Success / Error message -->
        <?php if (isset($_GET['sent']) && $_GET['sent'] == "1"): ?>
        <script>
            alert("✅ Message sent successfully!");
            window.history.replaceState({}, document.title, "contact.php");
        </script>
        <?php elseif (isset($_GET['sent']) && $_GET['sent'] == "0"): ?>
        <script>
            alert("❌ Message not sent. Please try again.");
            window.history.replaceState({}, document.title, "contact.php");
        </script>
        <?php endif; ?>

        <!-- ✅ FIXED FORM -->
        <form action="contact_send.php" method="POST">
          <div class="mb-3">
            <label class="form-label text-secondary">Your Name</label>
            <input type="text" name="name" class="form-control modern-input" placeholder="Enter your name" required>
          </div>

          <div class="mb-3">
            <label class="form-label text-secondary">Email</label>
            <input type="email" name="email" class="form-control modern-input" placeholder="Enter your email" required>
          </div>

          <div class="mb-3">
            <label class="form-label text-secondary">Message</label>
            <textarea name="message" class="form-control modern-input" rows="5" placeholder="Write your message..." required></textarea>
          </div>

          <!-- simple anti-spam hidden field -->
          <input type="text" name="website" style="display:none">

          <!-- ✅ must be submit -->
          <button type="submit" name="send" class="btn btn-warning text-dark w-100 fw-bold">
            Submit Message
          </button>
        </form>

        <div class="mt-4 rounded-4 overflow-hidden border border-secondary">
          <iframe
            src="https://www.google.com/maps?q=24%20Mile%20post%2C%20Bendiwewa%2C%20Sri%20Lanka&output=embed"
            width="100%" height="240" style="border:0;" loading="lazy"></iframe>
        </div>
      </div>
    </div>

  </div>
</div>

<?php include "footer.php"; ?>