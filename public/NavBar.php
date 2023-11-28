<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="icon" type="image/x-icon" href="../assets/client-logo.png">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/ea307fd923.js" crossorigin="anonymous"></script>
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
    
</body>
</html>