<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="index.css" />
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
  <?php include 'navbar.php'; ?>
</head>

<body>
  <nav>
    <a href="Home.php"><img src="../assets/logo.png" /></a>
    <div class="navigation">
      <ul class="animate__animated animate__slideInRight">
        <li><a href="#home"> Home </a></li>
        <li><a href="#services"> Services </a></li>
        <li><a href="#about"> About </a></li>
        <li><a href="#contact"> Contact </a></li>
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
  <!-- HOME -->
  <section id="home">
    <h2>TOOTH TALKS DENTAL CLINIC</h2>
    <p>Healthy teeth conversations</p>
    <div class="button">
      <a href="../php/login.php"> Get Started </a>
    </div>
  </section>
  <section class='services' id='services'>
    <h1>Our Services</h1>
    <p class='subhead'>Explore our dental services designed to enhance your oral health and well-being.</p>
    <div class='services-row'>
      <div class='service'>
      <i class="fas fa-tooth"></i>
        <h2>Oral Surgery</h2>
        <p>Closed/Open Extraction, 3rd Molar Extraction, Tooth Impaction, Pero-aprical Radiography</p>
      </div>
      <div class='service'>
      <i class="fas fa-tooth"></i>
        <h2>Dental Diagnosis & Consultation</h2>
        <p>Dental Checkup procedures</p>
      </div>
      <div class='service'>
      <i class="fas fa-tooth"></i>
        <h2>Oral Prophylaxis</h2>
        <p>Cavity & Onlay/Inlay Restoration</p>
      </div>
      <div class='service'>
      <i class="fas fa-tooth"></i>
        <h2>Esthetics</h2>
        <p>Composite/Porcelain, Veneers, Teeth Whitening</p>
      </div>
      <div class='service'>
      <i class="fas fa-tooth"></i>
        <h2>Periodontics</h2>
        <p>Scaling and Root Planning, Periodontal Surgery</p>
      </div>
      <div class='service'>
      <i class="fas fa-tooth"></i>
        <h2>Oral Rehabilitation</h2>
        <p>Restoration of all of the teeth in a given mouth</p>
      </div>
      <div class='service'>
      <i class="fas fa-tooth"></i>
        <h2>Orthodontics</h2>
        <p>Braces & Retainers</p>
      </div>
      <div class='service'>
      <i class="fas fa-tooth"></i>
        <h2>Prosthodontics</h2>
        <p>Full/Partial Dentures, Fixed/Removable, Prosthesis</p>
      </div>
      <div class='service'>
      <i class="fas fa-tooth"></i>
        <h2>Endodontics</h2>
        <p>Root Canal Treatment</p>
      </div>
    </div>
  </section>
  <section class='about' id='about'>
    <h1> About Us </h1>
    <div class='about-container'>
      <div class='about-content'>
        <p>
          Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Lacus laoreet non curabitur gravida arcu ac tortor dignissim. Eget lorem dolor sed viverra. Risus nec feugiat in fermentum posuere urna nec. Est ullamcorper eget nulla facilisi etiam dignissim diam. Morbi leo urna molestie at elementum eu facilisis sed. Turpis egestas sed tempus urna et pharetra pharetra massa. Amet volutpat consequat mauris nunc congue nisi vitae. Mattis molestie a iaculis at erat pellentesque adipiscing commodo elit.
        </p>
        <h3 class='about-subtitle'>
          Meet the Dentist
        </h3>
        <h4>
          Dr. Jimbo Beloso Plata
        </h4>
        <p class='dentist-details'>General Dentist <br /> Oral Surgery <br /> Orthodontist </p>
      </div>
      <div class='about-img'>
        <img src="../assets/about.jpg" alt='About Us' />
      </div>
    </div>
  </section>
  <section class="contact" id="contact">
      <h1> Contact Us </h1>
      <div class="contact-container">
        <div class="contact-map">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3874.7494824036626!2d121.00574750976905!3d13.793969896261686!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd0fd90cab807b%3A0x4743dd990cccad37!2sTooth%20Talks%20Dental%20Clinic!5e0!3m2!1sen!2sph!4v1700015589951!5m2!1sen!2sph"
            frameborder="0"
    style="border:0;"
    allowfullscreen=""
    aria-hidden="false"
    tabindex="0"></iframe>
        </div>
        <div class="contact-details">
          <ul class="contact-info">
            <li>
            <i class="fas fa-map-marker-alt"></i>
              <span>Makalintal Ave, Poblacion, Bauan, Batangas</span>
            </li>
            <li>
            <i class="fas fa-envelope"></i>
              <span> toothtalksdental@gmail.com </span>
            </li>
            <li>
            <i class="fas fa-phone"></i>
              <span> 0998 953 5223 </span>
            </li>
          </ul>
        </div>
      </div>
    </section>
    <footer>
    <div class="footer-content">
      <p>&copy; 2023 Tooth Talks Dental Clinic. All Rights Reserved.</p>
    </div>
  </footer>

</body>

</html>