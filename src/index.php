<?php
require "./function.php";
$showModal = false;
$perintah = "SELECT * FROM  eventlist";
$EventList = getDATA($perintah);

//mendapatkan username mahasiswa
if (isset($_SESSION["login-user"])) {
    if (isset($_SESSION["user_data"])) {
        // Extract only non-sensitive information
        $userData = $_SESSION["user_data"];
        $safeUserData = [
            'id' => $userData['id'],
            'email' => htmlspecialchars($userData['email'], ENT_QUOTES, 'UTF-8'),
            'username' => htmlspecialchars($userData['username'], ENT_QUOTES, 'UTF-8'),
            'role' => htmlspecialchars($userData['role'], ENT_QUOTES, 'UTF-8')
        ];
        $_SESSION["safe_user_data"] = $safeUserData;

        // Output the sanitized user data
        // var_dump($safeUserData);
    }
}

if (isset($_POST["daftar-event"])) {
    if (!isset($_SESSION["login-user"])) {
        $message = "silahkan login terlebih dahulu!";
        $showModal = true;
    } else {
        // var_dump($safeUserData);
        // var_dump($_POST["event-title-db"]);
        $hasilDaftar = daftarEvent($safeUserData, $_POST["event-title-db"]);
        if ($hasilDaftar === 1) {
            $message = "anda berhasil terdaftar!";
            $showModal = true;
        } else if ($hasilDaftar === -3) {
            $message = "tidak boleh daftar event yang sama!";
            $showModal = true;
        } else if ($hasilDaftar === -4) {
            $message = "anda tidak boleh daftar event lebih dari 5 kali!";
            $showModal = true;
        } else if ($hasilDaftar === -5) {
            $message = "kuota sudah melebihi batas!";
            $showModal = true;
        } else if ($hasilDaftar === -6) {
            $message = "query database gagal pada cek kuota";
            $showModal = true;
        } else {
            $message = "query database gagal!";
            $showModal = true;
        }
        // jika admin buat event, pastikan nama table untuk judul `UMN Hackfest 2024`
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Utama</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./styles/styles.css">
</head>
<style>
    .thumbnail {
        background-image: url("../Assets/d.jpg");
    }

    body,
    html {
        height: 100%;
        margin: 0;
        padding: 0;
    }
</style>

<body class="bg-secondary-subtle">
    <!--Paling atas-->
    <!-- navbar-->
    <nav class="navbar navbar-expand-lg position-fixed top-0 start-0 container-fluid z-2" style="background-color:  rgb(0, 0, 0, 0.1);">
        <div class="container-fluid px-5 py-3">
            <img src="../Assets/b.png" alt="UMN" height="50" width="100" class="mb-3 mb-lg-0">
            <button class="navbar-toggler mb-3 mb-lg-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <?php if (!isset($_SESSION["login-user"])) { ?>
                        <li class="nav-item">
                            <a class="nav-link mx-2" aria-current="page" href="./register.php">Register</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-2" href="./login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-2" href="#">Admin login</a>
                        </li>
                    <?php } ?>
                    <?php if (isset($_SESSION["login-user"])) { ?>
                        <li class="nav-item">
                            <a class="nav-link mx-2" href="./logout.php">Log out</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle mx-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Profile
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="./EditStudent.php">Edit/See your profile</a></li>
                                <li><a class="dropdown-item" href="./historyStudent.php">See registered event</a></li>
                            </ul>
                        </li>
                    <?php } ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle mx-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Action
                        </a>
                        <ul class="dropdown-menu">
                            <li class="dropdownEvent" data-target="#events-section"><a class="dropdown-item" href="#">See events</a></li>
                            <li class="dropdownEvent" data-target="#location-section"><a class="dropdown-item" href="#">Our location</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li class="dropdownEvent" data-target="#contact-section"><a class="dropdown-item mx-2" href="#">Contact us</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <section class="thumbnail container-fluid">
        <!-- dekorasi-->
        <div class="container d-flex justify-content-md-between" id="dekor">
            <img src="../Assets/c.png" alt="students" height="400" width="350" class="d-none d-md-block">
            <div class="container d-md-flex justify-content-md-center  align-items-md-end flex-md-column" id="text-dekor">
                <h1 class="text-white text-sm-center text-md-end d-none d-md-block" style="font-size: 3rem;">
                    Hello <?php echo isset($safeUserData["username"]) ? htmlspecialchars($safeUserData["username"], ENT_QUOTES, 'UTF-8') : 'students!'; ?>
                </h1>
                <h2 class="text-white text-sm-center text-md-end d-none d-md-block" style="font-size: 1.5rem;">See our latest events!</h2>
            </div>
        </div>
        <div id="dekor-small" class="container d-block d-md-none">
            <h1 class="text-white mx-auto text-center">Hello username</h1>
            <h2 class="text-white mx-auto text-center">See our latest event</h2>
            <img src="../Assets/c.png" alt="students" height="300" width="250" class="d-block mx-auto">
        </div>
    </section>

    <!--decoration-->
    <section class="search container-fluid">
    </section>

    <!--for showing events-->
    <section class="events bg-secondary-subtle container-fluid" id="events-section">
        <h3 class="text-center my-3">Our available events!</h3>
        <div class="container">
            <div class="row mt-4">
                <?php foreach ($EventList as $array) { ?>
                    <div class="col-12 col-md-6 col-lg-4 col-xxl-3 my-2">
                        <div class="card mx-auto" style="width: 100%;">
                            <img src="../img Event/<?php echo $array["namaGambar"]; ?>" class="card-img-top" alt="<?php echo $array["namaEvent"]; ?>">
                            <div class="card-body">
                                <div class="card-title"><?php echo $array["namaEvent"]; ?></div>
                                <p class="card-text"> <?php echo $array["tanggalEvent"] ?> | <?php echo $array["lokasi"] ?> | <?php echo $array["waktu"] ?></p>
                                <p class="card-text"><?php echo $array["description"]; ?></p>
                            </div>
                            <button type="button" class="btn btn-info ms-auto mx-3 my-3" style="width:5rem;" data-bs-toggle="modal" data-bs-target="#myModal" onclick="setEventDetails('<?php echo htmlspecialchars(json_encode($array)); ?>')">
                                Details
                            </button>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <!--Location-->
    <section class="Location container-fluid" id="location-section">
        <div class="row justify-content-center align-items-center" style="min-height: 50vh;">
            <div class="col-12 col-md-4">
                <div class="container d-flex justify-content-center align-items-center mt-5">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7932.106163038415!2d106.60929067770998!3d-6.256737999999995!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69fb56b25975f9%3A0x50c7d605ba8542f5!2sUniversitas%20Multimedia%20Nusantara!5e0!3m2!1sid!2sid!4v1729433546715!5m2!1sid!2sid" width="400" height="250" style="border:100px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
            <div class="col-12 col-md-8">
                <div class="container d-flex justify-content-center align-items-center mt-5">
                    <p class="text-white pb-5">
                        Situs Web Kampus Manajemen Acara di Universitas Multimedia Nusantara (MNP)
                        dirancang untuk memperlancar penyelenggaraan dan promosi acara kampus1.
                        Situs ini berfungsi sebagai pusat informasi bagi mahasiswa, staf pengajar,
                        dan pengunjung untuk menemukan informasi tentang acara mendatang, mendaftar,
                        dan mendapatkan informasi terkini tentang perubahan atau pengumuman.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-secondary-subtle" style="min-height: 50vh; padding:0; margin:0;"></section>

    <!--Footer-->
    <section class="mt-5" id="contact-section">
        <footer class="row row-cols-1 py-5 my-5 border-top text-white" style="background-color: #336699;">
            <div class="col-12 col-md-4 mb-3">
                <a href="./index.php" class="d-flex align-items-center mb-3 link-body-emphasis text-decoration-none">
                    <img src="../Assets/b.png" alt="logo umn" height="75" width="150">
                </a>
                <p class="text-white">© 2024</p>
            </div>

            <div class="col-12 col-md-4 mb-3">

            </div>

            <div class="col-12 col-md-4 mb-3">
                <h5>Our Social media platform</h5>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2"><a href="https://www.youtube.com/@UniversitasMultimediaNusantara" class="nav-link p-0 text-body-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="white" class="bi bi-youtube" viewBox="0 0 16 16">
                                <path d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.01 2.01 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.01 2.01 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31 31 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.01 2.01 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A100 100 0 0 1 7.858 2zM6.4 5.209v4.818l4.157-2.408z" />
                            </svg>
                        </a></li>
                    <li class="nav-item mb-2"><a href="https://www.instagram.com/universitasmultimedianusantara/" class="nav-link p-0 text-body-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="white" class="bi bi-instagram" viewBox="0 0 16 16">
                                <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334" />
                            </svg>
                        </a></li>
                    <li class="nav-item mb-2"><a href="https://api.whatsapp.com/send?phone=6281289015005&text=Halo" class="nav-link p-0 text-body-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="white" class="bi bi-whatsapp" viewBox="0 0 16 16">
                                <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232" />
                            </svg>
                        </a></li>
                </ul>
            </div>
        </footer>
    </section>

    <!-- Modal for event details-->
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="isiModal">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="eventDetails">Event Details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post">
                    <div class="modal-body" id="modalBody">
                        <div class="container">
                            <img src="" alt="tes" class="img-fluid my-3" id="fotoModal">
                            <div class="container">
                                <h5 id="event-title"></h5>
                                <input type="hidden" name="event-title-db" id="namaEventdb" value="">
                                <p id="event-tanggal"></p>
                                <p id="event-detail"></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="daftar-event" class="btn btn-primary">Daftar Event!</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--pop up alert-->
    <div class="modal fade" id="AlertPopup" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Alert!</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-di-alert">
                    ...
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="./Js/jquery-3.7.1.min.js"></script>
    <script src="./Js/index.js"></script>
    <script>
        $(document).ready(function() {
            <?php if ($showModal) { ?>
                var myModal = new bootstrap.Modal(document.getElementById('AlertPopup'), {
                    keyboard: false
                });
                var Message = "<?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>";
                $(".modal-di-alert").html(Message);
                myModal.show();

                // Redirect after 5 seconds if registration is successful
                <?php if (!isset($_SESSION["login-user"])) { ?>
                    setTimeout(function() {
                        window.location.href = 'index.php';
                    }, 2000);
                <?php } ?>
            <?php } ?>
        });
    </script>
</body>

</html>