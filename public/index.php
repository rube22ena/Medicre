<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
  <title>Medicre Hospital System - Home</title>


  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="../css/Style.css">
  <link rel="stylesheet" href="../css/categories.css">
</head>
<style>
  .carousel-item img {
    width: 1400px;
    height: 400px;
    object-fit: cover;
  }
</style>
<script src="js/script.js"></script>


</body>

</html>

<body>

  <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #0f9691; height: 100px;">
    <div class="container-fluid">
      <a class="navbar-brand" href="#"> <img src="pictures/logo-removebg-preview.png" alt="logo" height="50" width="50">
        <span>Medicre Hospital System</span></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item active">
            <a class="nav-link active" aria-current="page" href="index.php">🏠Home</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="#about" onclick="showAbout()">ℹ️ About</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="#categories" onclick="showcategories()">Categories</a>
          </li>

          <li class="nav-item active">
            <a class="nav-link" href="#site-footer">📞 Contact Us</a>
          </li>


        </ul>
      
        <div class="nav-item">
  <?php if (isset($_SESSION['user_id'])): ?>
    <!-- If logged in, show Logout instead -->
    <a class="nav-link login-link" href="http://localhost/MEDICREPROJECT/Medicre/public/logout.php">Logout</a>
  <?php else: ?>
    <!-- If not logged in, show Login -->
    <a class="nav-link login-link" href="http://localhost/MEDICREPROJECT/Medicre/public/login.php">Login</a>
  <?php endif; ?>
</div>
      </div>
    </div>
  </nav>
  <div id="carouselExampleCaptions" class="carousel slide carousel-fade" data-bs-ride="carousel">
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active"
        aria-current="true" aria-label="Slide 1"></button>
      <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
        aria-label="Slide 2"></button>
      <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"
        aria-label="Slide 3"></button>
    </div>
    <div class="carousel-inner" >
      <div class="carousel-item active">
        <img
          src="https://images.unsplash.com/photo-1758691463606-1493d79cc577?q=80&w=1332&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
          class="d-block w-100" alt="...">
        <div class="carousel-caption d-none d-md-block">
          <h2>Welcome to Medicre Hospital System</h2>
          <p>Book your appointments easily and get quality healthcare services.</p>
        </div>
      </div>
      <div class="carousel-item">
        <img
          src="https://images.unsplash.com/photo-1505751172876-fa1923c5c528?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
          class="d-block w-100" alt="...">
        <div class="carousel-caption d-none d-md-block">
          <h2>Welcome to Medicre Hospital System</h2>
          <p>Book your appointments easily and get quality healthcare services.</p>
        </div>
      </div>
      <div class="carousel-item">
        <img
          src="https://images.unsplash.com/photo-1624727828489-a1e03b79bba8?q=80&w=1171&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
          class="d-block w-100" alt="...">
        <div class="carousel-caption d-none d-md-block">
          <h2>Welcome to Medicre Hospital System</h2>
          <p>Book your appointments easily and get quality healthcare services.</p>
        </div>
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>
  <section id="categories" style="display:non;">
 <div class="categories-section">
  <div class="categories-header">
    <h2>Categories</h2>
  </div>

  <div class="categories-row">
    <div class="category">
      <img src="pictures\dermathology.png" alt="Dermatology Icon">
      <span>Dermatology</span>
    </div>
    <div class="category">
      <img src="pictures/medicine.png" alt="Medicine Icon">
      <span>Medicine</span>
    </div>
    <div class="category">
      <img src="pictures/dental.png" alt="Dental Icon">
      <span>Dental</span>
    </div>
    <div class="category">
      <img src="pictures/brain.png" alt="Brain Icon">
      <span>Brain</span>
    </div>
    <div class="category">
      <img src="pictures/orthopedic.png" alt="Orthopedic Icon">
      <span>Orthopedic</span>
    </div>
    <div class="category">
      <img src="pictures/ophthalmology.png" alt="Ophthalmology Icon">
      <span>Ophthalmology</span>
    </div>
    <div class="category">
      <img src="pictures\research_observation_pathology_laboratory.png" alt="Laboratory Icon">
      <span>Laboratory</span>
    </div>
  </div>
</div>
</section>
  <main>
    <!-- Centered header text -->
    <section class="hero-header">
      <h1>For Hospitals, Clinics, Doctors and Patients</h1>
      <p> Medicre is built for Everyone.</p>
    </section>

    <!-- Image left, text right -->
    <section class="hero">
      <div class="hero-image">
        <img src="http://localhost/MEDICREPROJECT/Medicre/uploads/indexdoctor.png" alt="Doctor illustration">
      </div>
      <div class="hero-text">
        <h3>✨ Experience No Wait Times</h3>
        <p>Instant appointment with Doctors. Patients can instantly book appointments with top doctors and avoid long
          queues. Healthrecord service is also available.</p>
        <a href="doctor_list.php" class="btn-doctors">👨‍⚕️ Our Doctors</a>
      </div>
    </section>
  </main>
 <section id="about" style="display:non;">
  
    
 <section id="promo">
  <div class="promo-container">
    
    <!-- Left Side: Text -->
    <div class="promo-text">
      <h2>CONVENIENCE FOR PATIENTS</h2>
      <h3>Convenient Lab Testing</h3>
      <p>
        Patients can now view their lab test results online with ease. Instead of waiting in long lines or handling piles of paperwork, reports are available instantly through a secure system. This makes it simple to track health records, share results with doctors, and stay informed about important details. With everything stored safely in one place, patients feel more confident and connected to their healthcare journey.
      </p>
      
     
    </div>

    <!-- Right Side: Illustration + Icons -->
    <div class="promo-illustration">
      <img src="http://localhost/MEDICREPROJECT/Medicre/uploads/medicaltesting.png" alt="App Promo" class="promo-image">
      
    </div>

  </div>
</section>
 <section id="records">
  <div class="records-container">
    
    <!-- Left Side: Illustration -->
    <div class="records-illustration">
      <img src="http://localhost/MEDICREPROJECT/Medicre/uploads/health-record.png" alt="Patient Records" class="records-image">
    </div>

    <!-- Right Side: Text -->
    <div class="records-text">
      <h2>ORGANIZE YOUR RECORDS</h2>
      <h3>Better Patient Records</h3>
      <ul>
        <li>✓ View Patient History</li>
        <li>✓ Records safely stored </li>
        <li>✓ Store details like Blood Group, Allergies, </li>
        
      </ul>
    </div>

  </div>
</section>
</section>
  
  

  <footer id="site-footer" style="background-color: #0f9691;" text-white pt-4 pb-3>
    <div class="container">
      <div class="row">
        <!-- Left: Branding and Award -->
        <div class="col-md-6 mb-3">
          <a class="navbar-brand" href="#"> <img src="pictures/logo-removebg-preview.png" alt="logo" height="50"
              width="50">
            <span>Medicre Hospital System</span></a>

        </div>

        <!-- Right: Contact Info -->
        <div class="col-md-6">
          <h5>Contact Us</h5>
          <ul class="list-unstyled">
            <li><strong>Address:</strong> Kathmandu,Nepal</li>
            <li><strong>Email:</strong> info@Medicre.org | Medicrenepal@gmail.com</li>
            <li><strong>Phone:</strong> +977-9800000008 | +977-9888989898</li>
          </ul>
        </div>
      </div>
    </div>
  </footer>




  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"></script>


</body>

</html>