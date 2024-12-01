<?php
require_once __DIR__ . '/database/db_connection.php';
require_once __DIR__ . '../src/controller/PesanController.php';
require_once __DIR__ . '../src/controller/JadwalBusController.php';

// Menghitung jumlah bus
$stmtBus = $pdo->query("SELECT COUNT(*) AS total_bus FROM bus");
$totalBus = $stmtBus->fetch(PDO::FETCH_ASSOC)['total_bus'];

// Menghitung jumlah staf (petugas, admin, superadmin)
$stmtStaff = $pdo->query("SELECT COUNT(*) AS total_staff FROM user WHERE level IN ('admin', 'petugas', 'superadmin')");
$totalStaff = $stmtStaff->fetch(PDO::FETCH_ASSOC)['total_staff'];

// Menghitung jumlah klien (customer)
$stmtClients = $pdo->query("SELECT COUNT(*) AS total_clients FROM user WHERE level = 'customer'");
$totalClients = $stmtClients->fetch(PDO::FETCH_ASSOC)['total_clients'];

$pesanController = new PesanController($pdo);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form yang dikirimkan menggunakan AJAX
    $id_user = $_POST['id_user'];
    $pesan = $_POST['pesan'];

    // Cek jika id_user dan pesan tidak kosong
    if (!empty($id_user) && !empty($pesan)) {
        // Simpan pesan ke database
        if ($pesanController->create($id_user, $pesan)) {
            echo "Pesan berhasil dikirim!";
        } else {
            echo "Gagal mengirim pesan. Coba lagi.";
        }
    } else {
        echo "ID User dan Pesan tidak boleh kosong.";
    }
}

$jadwalBusController = new JadwalBusController($pdo);
$jadwalBuses = $jadwalBusController->getAllSchedules();
?>

<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Tiket Transportasi Online</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="public/landing/img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="public/landing/lib/animate/animate.min.css" rel="stylesheet">
    <link href="public/landing/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="public/landing/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="public/landing/css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="public/landing/css/style.css" rel="stylesheet">
</head>

<body>
    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <!-- <div id="spinner"
            class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div> -->
        <!-- Spinner End -->

        <!-- Header Start -->
        <div class="container-fluid bg-dark px-0">
            <div class="row gx-0">
                <div class="col-lg-3 bg-dark d-none d-lg-block">
                    <a href="#"
                        class="navbar-brand w-100 h-100 m-0 p-0 d-flex align-items-center justify-content-center">
                        <h1 class="m-0 text-primary text-uppercase">Ticket Bus</h1>
                    </a>
                </div>
                <div class="col-lg-9">
                    <div class="row gx-0 bg-white d-none d-lg-flex">
                        <div class="col-lg-7 px-5 text-start">
                            <div class="h-100 d-inline-flex align-items-center py-2 me-4">
                                <i class="fa fa-envelope text-primary me-2"></i>
                                <p class="mb-0">TicketTransport@gmail.com</p>
                            </div>
                            <div class="h-100 d-inline-flex align-items-center py-2">
                                <i class="fa fa-phone-alt text-primary me-2"></i>
                                <p class="mb-0">+621 1234 5678</p>
                            </div>
                        </div>
                        <div class="col-lg-5 px-5 text-end">
                            <div class="d-inline-flex align-items-center py-2">
                                <a class="me-3" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="me-3" href=""><i class="fab fa-twitter"></i></a>
                                <a class="me-3" href=""><i class="fab fa-linkedin-in"></i></a>
                                <a class="me-3" href=""><i class="fab fa-instagram"></i></a>
                                <a class="" href=""><i class="fab fa-youtube"></i></a>
                            </div>
                        </div>
                    </div>
                    <nav class="navbar navbar-expand-lg bg-dark navbar-dark p-3 p-lg-0">
                        <a href="index.html" class="navbar-brand d-block d-lg-none">
                            <h1 class="m-0 text-primary text-uppercase">Hotelier</h1>
                        </a>
                        <button type="button" class="navbar-toggler" data-bs-toggle="collapse"
                            data-bs-target="#navbarCollapse">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                            <div class="navbar-nav mr-auto py-0">
                                <a href="index.php" class="nav-item nav-link active">Home</a>
                                <a href="public/views/about.html" class="nav-item nav-link">About</a>
                                <a href="public/views/service.html" class="nav-item nav-link">Services</a>
                                <a href="public/views/room.html" class="nav-item nav-link">Rooms</a>
                                <div class="nav-item dropdown">
                                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                                    <div class="dropdown-menu rounded-0 m-0">
                                        <a href="booking.html" class="dropdown-item">Booking</a>
                                        <a href="team.html" class="dropdown-item">Our Team</a>
                                        <a href="testimonial.html" class="dropdown-item">Testimonial</a>
                                    </div>
                                </div>
                                <a href="contact.html" class="nav-item nav-link">Contact</a>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
        <!-- Header End -->


        <!-- Carousel Start -->
        <div class="container-fluid p-0 mb-5">
            <div id="header-carousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img class="w-100" src="public/landing/img/carousel-1.jpg" alt="Image">
                        <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                            <div class="p-3" style="max-width: 700px;">
                                <h6 class="section-title text-white text-uppercase mb-3 animated slideInDown">
                                    Tiket Bus Online</h6>
                                <h1 class="display-3 text-white mb-4 animated slideInDown">Temukan Tiket Bus Yang
                                    Terbaik</h1>
                                <a href="public/register/register.php"
                                    class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft">
                                    Register</a>
                                <a href="public/register/login.php"
                                    class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft">
                                    Login</a>

                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img class="w-100" src="public/landing/img/carousel-2.jpg" alt="Image">
                        <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                            <div class="p-3" style="max-width: 700px;">
                                <h6 class="section-title text-white text-uppercase mb-3 animated slideInDown">
                                    Tiket Bus Online</h6>
                                <h1 class="display-3 text-white mb-4 animated slideInDown">Temukan Tiket Bus Yang
                                </h1>
                                <a href="public/register/register.php"
                                    class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft">
                                    Register</a>
                                <a href="public/register/login.php"
                                    class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft">
                                    Login</a>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#header-carousel"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#header-carousel"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
        <!-- Carousel End -->


        <!-- Booking Start -->
        <div class="container-fluid booking pb-5 wow fadeIn" data-wow-delay="0.1s">
            <div class="container">
                <div class="bg-white shadow" style="padding: 35px;">
                    <div class="row g-2">
                        <div class="col-md-10">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="form-select">
                                        <input type="text" class="form-control datetimepicker-input"
                                            placeholder="Tempat Awal" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-select">
                                        <input type="text" class="form-control datetimepicker-input"
                                            placeholder="Destinasi" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Booking End -->


        <!-- About Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="row g-5 align-items-center">
                    <div class="col-lg-6">
                        <h6 class="section-title text-start text-primary text-uppercase">About Us</h6>
                        <h1 class="mb-4">Selamat Datang Di <br> <span class="text-primary text-uppercase">Ticket
                                Bus</span>
                        </h1>
                        <p class="mb-4"></p><span class="text-primary text-uppercase">Ticket
                            Bus</span> adalah website pemesanan tiket bus online yang terpercaya dan terjamin oleh
                        perusahaan bus
                        </p>
                        <div class="row g-3 pb-4">
                            <div class="col-sm-4 wow fadeIn" data-wow-delay="0.1s">
                                <div class="border rounded p-1">
                                    <div class="border rounded text-center p-4">
                                        <i class="fa fa-bus fa-2x text-primary mb-2"></i>
                                        <h2 class="mb-1" data-toggle="counter-up"><?= $totalBus ?></h2>
                                        <p class="mb-0">Bus</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 wow fadeIn" data-wow-delay="0.3s">
                                <div class="border rounded p-1">
                                    <div class="border rounded text-center p-4">
                                        <i class="fa fa-user-shield fa-2x text-primary mb-2"></i>
                                        <h2 class="mb-1" data-toggle="counter-up"><?= $totalStaff ?></h2>
                                        <p class="mb-0">Staffs</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 wow fadeIn" data-wow-delay="0.5s">
                                <div class="border rounded p-1">
                                    <div class="border rounded text-center p-4">
                                        <i class="fa fa-users fa-2x text-primary mb-2"></i>
                                        <h2 class="mb-1" data-toggle="counter-up"><?= $totalClients ?></h2>
                                        <p class="mb-0">Clients</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-6">
                        <div class="row g-3">
                            <div class="col-6 text-end">
                                <img class="img-fluid rounded w-75 wow zoomIn" data-wow-delay="0.1s"
                                    src="public/landing/img/about-1.jpg" style="margin-top: 25%;">
                            </div>
                            <div class="col-6 text-start">
                                <img class="img-fluid rounded w-100 wow zoomIn" data-wow-delay="0.3s"
                                    src="public/landing/img/about-2.jpg">
                            </div>
                            <div class="col-6 text-end">
                                <img class="img-fluid rounded w-50 wow zoomIn" data-wow-delay="0.5s"
                                    src="public/landing/img/about-3.jpg">
                            </div>
                            <div class="col-6 text-start">
                                <img class="img-fluid rounded w-75 wow zoomIn" data-wow-delay="0.7s"
                                    src="public/landing/img/about-4.jpg">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- About End -->


        <!-- Tikcket Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                    <h6 class="section-title text-center text-primary text-uppercase">Our Tickets</h6>
                    <h1 class="mb-5">Explore Our <span class="text-primary text-uppercase">Tickets</span></h1>
                </div>
                <div class="row g-4">
                    <?php
                    foreach ($jadwalBuses as $jadwal) {
                        // Validasi gambar bus
                        $gambarBus = isset($jadwal['gambar']) && !empty($jadwal['gambar'])
                            ? 'public/landing/img/bus/' . $jadwal['gambar']
                            : 'public/landing/img/bus/default.jpg'; // Gambar default jika tidak ada
                    
                        // Validasi waktu keberangkatan
                        $waktuKeberangkatan = !empty($jadwal['datetime_keberangkatan'])
                            ? date('d M Y H:i', strtotime($jadwal['datetime_keberangkatan']))
                            : 'Belum ditentukan';

                        // Validasi waktu sampai
                        $waktuSampai = !empty($jadwal['datetime_sampai'])
                            ? date('d M Y H:i', strtotime($jadwal['datetime_sampai']))
                            : 'Belum ditentukan';

                        // Informasi tambahan
                        $hargaTiket = number_format($jadwal['harga'], 0, ',', '.'); // Format harga dalam bentuk Rp.
                        $namaRute = $jadwal['rute_keberangkatan'] . ' - ' . $jadwal['rute_tujuan'];
                        ?>
                        <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                            <div class="room-item shadow rounded overflow-hidden">
                                <div class="position-relative">
                                    <img class="img-fluid" src="<?php echo $gambarBus; ?>" alt="Bus Image">
                                    <small
                                        class="position-absolute start-0 top-100 translate-middle-y bg-primary text-white rounded py-1 px-3 ms-4">Rp.
                                        <?php echo $hargaTiket; ?>/Tiket</small>
                                </div>
                                <div class="p-4 mt-2">
                                    <div class="d-flex justify-content-between mb-3">
                                        <h5 class="mb-0"><?php echo $namaRute; ?></h5>
                                        <div class="ps-2">
                                            <small class="fa fa-star text-primary"></small>
                                            <small class="fa fa-star text-primary"></small>
                                            <small class="fa fa-star text-primary"></small>
                                            <small class="fa fa-star text-primary"></small>
                                            <small class="fa fa-star text-primary"></small>
                                        </div>
                                    </div>
                                    <div class="d-flex mb-3">
                                        <small class="border-end me-3 pe-3"><i class="fa fa-bed text-primary me-2"></i>2
                                            Kursi Bersandingan</small>
                                        <?php if (!empty($jadwal['rute_transit'])) { ?>
                                            <small class="border-end me-3 pe-3"><i
                                                    class="fa fa-train text-primary me-2"></i>Transit</small>
                                        <?php } ?>
                                        <small><i class="fa fa-wifi text-primary me-2"></i>Wifi</small>
                                    </div>

                                    <p class="text-body mb-3">
                                        <?php echo $waktuKeberangkatan . ' - ' . $waktuSampai; ?>
                                    </p>
                                    <div class="d-flex justify-content-between">
                                        <a class="btn btn-sm btn-primary rounded py-2 px-4" href="">View Detail</a>
                                        <a class="btn btn-sm btn-dark rounded py-2 px-4" href="">Book Now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>

        <!-- Ticket End -->

        <!-- Service Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                    <h6 class="section-title text-center text-primary text-uppercase">Layanan Kami</h6>
                    <h1 class="mb-5">Jelajahi <span class="text-primary text-uppercase">Layanan</span> Kami</h1>
                </div>
                <div class="row g-4">
                    <!-- Poin 1: Reservasi Bus -->
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <a class="service-item rounded" href="">
                            <div class="service-icon bg-transparent border rounded p-1">
                                <div
                                    class="w-100 h-100 border rounded d-flex align-items-center justify-content-center">
                                    <i class="fa fa-bus fa-2x text-primary"></i>
                                </div>
                            </div>
                            <h5 class="mb-3">Reservasi Bus</h5>
                            <p class="text-body mb-0">Pesan kursi Anda dengan mudah melalui layanan reservasi bus kami.
                            </p>
                        </a>
                    </div>
                    <!-- Poin 2: Jadwal Bus -->
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.2s">
                        <a class="service-item rounded" href="">
                            <div class="service-icon bg-transparent border rounded p-1">
                                <div
                                    class="w-100 h-100 border rounded d-flex align-items-center justify-content-center">
                                    <i class="fa fa-clock fa-2x text-primary"></i>
                                </div>
                            </div>
                            <h5 class="mb-3">Jadwal Bus</h5>
                            <p class="text-body mb-0">Lihat jadwal keberangkatan dan kedatangan bus secara real-time.
                            </p>
                        </a>
                    </div>
                    <!-- Poin 3: Rute Perjalanan -->
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                        <a class="service-item rounded" href="">
                            <div class="service-icon bg-transparent border rounded p-1">
                                <div
                                    class="w-100 h-100 border rounded d-flex align-items-center justify-content-center">
                                    <i class="fa fa-map-marker-alt fa-2x text-primary"></i>
                                </div>
                            </div>
                            <h5 class="mb-3">Rute Perjalanan</h5>
                            <p class="text-body mb-0">Temukan informasi lengkap tentang rute perjalanan bus.</p>
                        </a>
                    </div>
                    <!-- Poin 4: Layanan Bus -->
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.4s">
                        <a class="service-item rounded" href="">
                            <div class="service-icon bg-transparent border rounded p-1">
                                <div
                                    class="w-100 h-100 border rounded d-flex align-items-center justify-content-center">
                                    <i class="fa fa-concierge-bell fa-2x text-primary"></i>
                                </div>
                            </div>
                            <h5 class="mb-3">Layanan Bus</h5>
                            <p class="text-body mb-0">Nikmati layanan bus dengan fasilitas terbaik dan nyaman.</p>
                        </a>
                    </div>
                    <!-- Poin 5: Tiket Online -->
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                        <a class="service-item rounded" href="">
                            <div class="service-icon bg-transparent border rounded p-1">
                                <div
                                    class="w-100 h-100 border rounded d-flex align-items-center justify-content-center">
                                    <i class="fa fa-ticket-alt fa-2x text-primary"></i>
                                </div>
                            </div>
                            <h5 class="mb-3">Tiket Online</h5>
                            <p class="text-body mb-0">Pesan tiket secara online dengan mudah dan cepat.</p>
                        </a>
                    </div>
                    <!-- Poin 6: Pelacakan Bus -->
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.6s">
                        <a class="service-item rounded" href="">
                            <div class="service-icon bg-transparent border rounded p-1">
                                <div
                                    class="w-100 h-100 border rounded d-flex align-items-center justify-content-center">
                                    <i class="fa fa-route fa-2x text-primary"></i>
                                </div>
                            </div>
                            <h5 class="mb-3">Pelacakan Bus</h5>
                            <p class="text-body mb-0">Pantau lokasi bus secara langsung melalui fitur pelacakan.</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Service End -->


        <!-- Testimonial Start -->
        <div class="container-xxl testimonial my-5 py-5 bg-dark wow zoomIn" data-wow-delay="0.1s">
            <div class="container">
                <div class="owl-carousel testimonial-carousel py-5">
                    <div class="testimonial-item position-relative bg-white rounded overflow-hidden">
                        <p>Tempor stet labore dolor clita stet diam amet ipsum dolor duo ipsum rebum stet dolor amet
                            diam stet. Est stet ea lorem amet est kasd kasd et erat magna eos</p>
                        <div class="d-flex align-items-center">
                            <img class="img-fluid flex-shrink-0 rounded" src="public/landing/img/testimonial-1.jpg"
                                style="width: 45px; height: 45px;">
                            <div class="ps-3">
                                <h6 class="fw-bold mb-1">Client Name</h6>
                                <small>Profession</small>
                            </div>
                        </div>
                        <i class="fa fa-quote-right fa-3x text-primary position-absolute end-0 bottom-0 me-4 mb-n1"></i>
                    </div>
                    <div class="testimonial-item position-relative bg-white rounded overflow-hidden">
                        <p>Tempor stet labore dolor clita stet diam amet ipsum dolor duo ipsum rebum stet dolor amet
                            diam stet. Est stet ea lorem amet est kasd kasd et erat magna eos</p>
                        <div class="d-flex align-items-center">
                            <img class="img-fluid flex-shrink-0 rounded" src="public/landing/img/testimonial-2.jpg"
                                style="width: 45px; height: 45px;">
                            <div class="ps-3">
                                <h6 class="fw-bold mb-1">Client Name</h6>
                                <small>Profession</small>
                            </div>
                        </div>
                        <i class="fa fa-quote-right fa-3x text-primary position-absolute end-0 bottom-0 me-4 mb-n1"></i>
                    </div>
                    <div class="testimonial-item position-relative bg-white rounded overflow-hidden">
                        <p>Tempor stet labore dolor clita stet diam amet ipsum dolor duo ipsum rebum stet dolor amet
                            diam stet. Est stet ea lorem amet est kasd kasd et erat magna eos</p>
                        <div class="d-flex align-items-center">
                            <img class="img-fluid flex-shrink-0 rounded" src="public/landing/img/testimonial-3.jpg"
                                style="width: 45px; height: 45px;">
                            <div class="ps-3">
                                <h6 class="fw-bold mb-1">Client Name</h6>
                                <small>Profession</small>
                            </div>
                        </div>
                        <i class="fa fa-quote-right fa-3x text-primary position-absolute end-0 bottom-0 me-4 mb-n1"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- Testimonial End -->


        <!-- Newsletter Start -->
        <div class="container newsletter mt-5 wow fadeIn" data-wow-delay="0.1s">
            <div class="row justify-content-center">
                <div class="col-lg-10 border rounded p-1">
                    <div class="border rounded text-center p-1">
                        <div class="bg-white rounded text-center p-5">
                            <h4 class="mb-4">Hubungi <span class="text-primary text-uppercase">Kami</span></h4>
                            <form id="pesanForm">
                                <div class="position-relative mx-auto" style="max-width: 400px;">
                                    <!-- Input untuk ID User -->
                                    <input class="form-control w-100 py-3 ps-4 pe-5 mb-3" type="text" name="id_user"
                                        id="id_user" placeholder="Masukkan ID Anda" required>
                                </div>
                                <div class="position-relative mx-auto" style="max-width: 400px;">
                                    <!-- Input untuk Pesan -->
                                    <textarea class="form-control w-100 py-3 ps-4 pe-5 mb-3" name="pesan" id="pesan"
                                        placeholder="Masukkan pesan Anda" rows="4" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary py-2 px-4 mt-2">Kirim Pesan</button>
                            </form>
                            <div id="responseMessage" class="mt-3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Handle form submission with AJAX
            document.getElementById('pesanForm').addEventListener('submit', function (e) {
                e.preventDefault(); // Prevent default form submission

                // Get form data
                var id_user = document.getElementById('id_user').value;
                var pesan = document.getElementById('pesan').value;

                // Prepare data for AJAX request
                var formData = new FormData();
                formData.append('id_user', id_user);
                formData.append('pesan', pesan);

                // AJAX request
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'index.php', true);
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        // Show response message from server
                        document.getElementById('responseMessage').innerHTML = xhr.responseText;
                    }
                };
                xhr.send(formData);
            });
        </script>


        <!-- Newsletter Start -->


        <!-- Footer Start -->
        <div class="container-fluid bg-dark text-light footer wow fadeIn" data-wow-delay="0.1s">
            <div class="container pb-5">
                <div class="row g-5">
                    <div class="col-md-6 col-lg-4">
                        <div class="bg-primary rounded p-4">
                            <a href="index.html">
                                <h1 class="text-white text-uppercase mb-3">Ticket Bus</h1>
                            </a>
                            <p class="text-white mb-0">
                                Lorem ipsum dolor sit, amet consectetur adipisicing elit. Ullam labore velit quod quam
                                unde, sapiente laborum dolor excepturi reprehenderit architecto facere ad dolores
                                necessitatibus minima. Asperiores iusto tempora doloribus nam.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <h6 class="section-title text-start text-primary text-uppercase mb-4">Contact</h6>
                        <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>123 Street, New York, USA</p>
                        <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+012 345 67890</p>
                        <p class="mb-2"><i class="fa fa-envelope me-3"></i>info@example.com</p>
                        <div class="d-flex pt-2">
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-youtube"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-12">
                        <div class="row gy-5 g-4">
                            <div class="col-md-6">
                                <h6 class="section-title text-start text-primary text-uppercase mb-4">Company</h6>
                                <a class="btn btn-link" href="">About Us</a>
                                <a class="btn btn-link" href="">Contact Us</a>
                                <a class="btn btn-link" href="">Privacy Policy</a>
                                <a class="btn btn-link" href="">Terms & Condition</a>
                                <a class="btn btn-link" href="">Support</a>
                            </div>
                            <div class="col-md-6">
                                <h6 class="section-title text-start text-primary text-uppercase mb-4">Services</h6>
                                <a class="btn btn-link" href="">Food & Restaurant</a>
                                <a class="btn btn-link" href="">Spa & Fitness</a>
                                <a class="btn btn-link" href="">Sports & Gaming</a>
                                <a class="btn btn-link" href="">Event & Party</a>
                                <a class="btn btn-link" href="">GYM & Yoga</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="copyright">
                    <div class="row">
                        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                            &copy; <a class="border-bottom" href="#">Your Site Name</a>, All Right Reserved.

                            <!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
                            Designed By <a class="border-bottom" href="https://htmlcodex.com">HTML Codex</a>
                        </div>
                        <div class="col-md-6 text-center text-md-end">
                            <div class="footer-menu">
                                <a href="">Home</a>
                                <a href="">Cookies</a>
                                <a href="">Help</a>
                                <a href="">FQAs</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="public/landing/lib/wow/wow.min.js"></script>
    <script src="public/landing/lib/easing/easing.min.js"></script>
    <script src="public/landing/lib/waypoints/waypoints.min.js"></script>
    <script src="public/landing/lib/counterup/counterup.min.js"></script>
    <script src="public/landing/lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="public/landing/lib/tempusdominus/js/moment.min.js"></script>
    <script src="public/landing/lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="public/landing/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="public/landing/js/main.js"></script>
</body>

</html>