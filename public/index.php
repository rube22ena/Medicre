
<!DOCTYPE html>
<html>

<head>
  <title>Medicre Hospital System - Home</title>
  <link rel="stylesheet" href="../css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
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


  <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #0f9691;">
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
            <a class="nav-link" href="#about"onclick="showAbout()">ℹ️ About</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="#contact" onclick="showContact(event)">📞 Contact Us</a>
          </li>

        </ul>
        <!-- <form class="d-flex">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-success" type="submit">Search</button>
        </form> -->
        <div class="nav-item">
         <a class="nav-link login-link" href="http://localhost/MEDICREPROJECT/Medicre/public/login.php"> Login</a>
       
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
    <div class="carousel-inner">
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
  <section id="about"style="display:non;">
     <div class="container my-4">

    <div class="row mb-2">
      <div class="col-md-6">
        <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
          <div class="col p-4 d-flex flex-column position-static">
            <strong class="d-inline-block mb-2 text-primary-emphasis">Medicre</strong>
            <h3 class="mb-0">Medical Records</h3>
            <div class="mb-1 text-body-secondary">Dec 12</div>
            <p class="card-text mb-auto">Patient records are safely kept in the cloud, making it easy to see medical
              history, important details like blood group or allergies, and lab reports.</p>
            <a href="#" class="icon-link gap-1 icon-link-hover stretched-link">
              Continue reading
              <svg class="bi" aria-hidden="true">
                <use xlink:href="#chevron-right"></use>
              </svg> </a>
          </div>
          <div class="col-auto d-none d-lg-block">
            <img class="bd-placeholder-img " height="250" preserveAspectRatio="xMidYMid slice" role="img" width="200"
              src="https://www.shutterstock.com/image-vector/medical-record-logo-vector-260nw-1324999502.jpg" alt="">
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
          <div class="col p-4 d-flex flex-column position-static">
            <strong class="d-inline-block mb-2 text-success-emphasis">Medicre</strong>
            <h3 class="mb-0">Appointment</h3>
            <div class="mb-1 text-body-secondary">Dec 11</div>
            <p class="mb-auto">We’ve made life easier for patients and doctors. Now patients can instantly book
              appointments with the top doctors and avoid having to stand in long queues.</p>
            <a href="#" class="icon-link gap-1 icon-link-hover stretched-link">
              Continue reading
              <svg class="bi" aria-hidden="true">
                <use xlink:href="#chevron-right"></use>
              </svg> </a>
          </div>
          <div class="col-auto d-none d-lg-block">
            <img class="bd-placeholder-img " height="250" preserveAspectRatio="xMidYMid slice" role="img" width="200"
              src="https://static.vecteezy.com/system/resources/previews/006/095/578/original/an-appointment-icon-flat-outline-concept-vector.jpg"
              alt="">
          </div>
        </div>
      </div>
    </div>
    </div>
  </section>
  </div>
  <div class="container my-4">
    <div class="row mb-2">
      <div class="col-md-6">
        <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
          <div class="col p-4 d-flex flex-column position-static">
            <strong class="d-inline-block mb-2 text-primary-emphasis">Medicre</strong>
            <h3 class="mb-0">Lab Reports</h3>
            <div class="mb-1 text-body-secondary">Dec 12</div>
            <p class="card-text mb-auto">Patients can quickly view their lab test results online, making it easier to track health records without delays or paperwork.</p>
            <a href="#" class="icon-link gap-1 icon-link-hover stretched-link">
              Continue reading
              <svg class="bi" aria-hidden="true">
                <use xlink:href="#chevron-right"></use>
              </svg> </a>
          </div>
          <div class="col-auto d-none d-lg-block">
            <img class="bd-placeholder-img " height="250" preserveAspectRatio="xMidYMid slice" role="img" width="200"
              src="https://as1.ftcdn.net/v2/jpg/05/04/71/44/1000_F_504714429_wk0IpfkSxrfwURva8gFcDBGXM6AV1yki.jpg" alt="">
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
          <div class="col p-4 d-flex flex-column position-static">
            <strong class="d-inline-block mb-2 text-success-emphasis">Medicre</strong>
            <h3 class="mb-0">Doctors & Staff</h3>
            <div class="mb-1 text-body-secondary">Dec 11</div>
            <p class="mb-auto">Profiles of doctors and hospital staff are available with their specialties and schedules, helping patients connect with the right medical professionals easily.</p>
            <a href="#" class="icon-link gap-1 icon-link-hover stretched-link">
              Continue reading
              <svg class="bi" aria-hidden="true">
                <use xlink:href="#chevron-right"></use>
              </svg> </a>
          </div>
          <div class="col-auto d-none d-lg-block">
            <img class="bd-placeholder-img " height="250" preserveAspectRatio="xMidYMid slice" role="img" width="200"
              src="https://tse4.mm.bing.net/th/id/OIP.lNhp2Pfc368QAIfKf5QzjQAAAA?cb=ucfimg2&ucfimg=1&w=286&h=320&rs=1&pid=ImgDetMain&o=7&rm=3"
              alt="">
          </div>
        </div>
      </div>
    </div>
  </div>
<footer id="site-footer"  style="background-color: #0f9691;" text-white pt-4 pb-3>
  <div class="container">
    <div class="row">
      <!-- Left: Branding and Award -->
      <div class="col-md-6 mb-3">
       <a class="navbar-brand" href="#"> <img src="pictures/logo-removebg-preview.png" alt="logo" height="50" width="50">
        <span>Medicre Hospital System</span></a>
       
      </div>

      <!-- Right: Contact Info -->
      <div class="col-md-6">
        <h5>Contact Us</h5>
        <ul class="list-unstyled">
          <li><strong>Address:</strong>  Kathmandu,Nepal</li>
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



