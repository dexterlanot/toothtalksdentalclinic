<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation</title>
    <link rel="stylesheet" href="nav.css">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="icon" type="image/x-icon" href="../assets/client-logo.png">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/ea307fd923.js" crossorigin="anonymous"></script>
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
  />
  <title>Tooth Talks Dental Clinic</title>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const navLinksContainer = document.querySelector(".navigation");

      navLinksContainer.addEventListener("click", function(event) {
        if (event.target.tagName === "A") {
          document.querySelectorAll("nav .navigation ul li a").forEach((link) => {
            link.classList.remove("active");
          });
          event.target.classList.add("active");
        }
      });
    });
  </script>
  <script>
document.addEventListener("DOMContentLoaded", function() {
  const navLinksContainer = document.querySelector(".navigation");
  const menuToggle = document.querySelector(".menu-toggle");

  menuToggle.addEventListener("click", function() {
    navLinksContainer.classList.toggle("open");
  });

  navLinksContainer.addEventListener("click", function(event) {
    if (event.target.tagName === "A") {
      document.querySelectorAll("nav .navigation ul").forEach((link) => {
        link.classList.remove("active");
      });
      event.target.classList.add("active");
      navLinksContainer.classList.remove("open");
    }
  });
});

  </script>
</head>
<body>
<nav>
    <a href="../public/Home.php"><img src="../assets/logo.png" /></a>
    <div class="navigation">
      <ul class="animate__animated animate__slideInRight">
        <li><a href="../public/Home.php"> Home </a></li>
        <li><a href="../public/Home.php#services"> Services </a></li>
        <li><a href="../public/Home.php#about"> About </a></li>
        <li><a href="../public/Home.php#contact"> Contact </a></li>
        <li>
          <a href="../php/signup.php">
            <span class="signup">Sign Up</span>
          </a>
        </li>
        <li>
          <a href="../php/login.php"> <span class="login">Login</span> </a>
        </li>
      </ul>
    </div>
    <div class="menu-toggle">&#9776;</div>
  </nav>
</body>
</html>