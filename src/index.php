<?php
require "./function.php";
$perintah = "SELECT * FROM  eventlist";
$EventList = getDATA($perintah);
// var_dump($EventList);
if (isset($_POST['eventdetails'])) {
    $eventDetails = json_decode($_POST['eventdetails'], true);
    var_dump($eventDetails);
}

if (isset($_POST["daftar-event"])) {
    if (!isset($_SESSION["login-user"])) {
        echo
        "
        <script>
            alert('silakan login terlebih dahulu');
        </script>
        ";
    } else {
        echo
        "
        <script>
            alert('berhasil terdaftar!');
        </script>
        ";
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
</style>

<body>
    <!--Paling atas-->
    <section class="thumbnail container-fluid">
        <!-- navbar-->
        <nav class="navbar navbar-expand-lg position-absolute top-0 start-0 container-fluid z-2" style="background-color:  rgb(0, 0, 0, 0.1);">
            <div class="container-fluid px-5 py-3">
                <img src="../Assets/b.png" alt="UMN" height="50" width="100" class="mb-3 mb-lg-0">
                <button class="navbar-toggler mb-3 mb-lg-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link mx-2" aria-current="page" href="#">Register</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-2" href="./login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-2" href="#">Admin login</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle mx-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                            </a>
                            <ul class="dropdown-menu">
                                <li id="dropdownEvent" data-target = "#events-section"><a class="dropdown-item" href="#">See events</a></li>
                                <li><a class="dropdown-item" href="#">Our location</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item mx-2" href="#">Contact us</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- dekorasi-->
        <div class="container d-flex justify-content-md-between" id="dekor">
            <img src="../Assets/c.png" alt="students" height="400" width="350" class="d-none d-md-block">
            <div class="container d-md-flex justify-content-md-center  align-items-md-end flex-md-column" id="text-dekor">
                <h1 class="text-white text-sm-center text-md-end d-none d-md-block" style="font-size: 3rem;">Hello username</h1>
                <h2 class="text-white text-sm-center text-md-end d-none d-md-block" style="font-size: 1.5rem;">See our latest events!</h2>
            </div>
        </div>
        <div id="dekor-small" class="container d-block d-md-none">
            <h1 class="text-white mx-auto text-center">Hello username</h1>
            <h2 class="text-white mx-auto text-center">See our latest event</h2>
            <img src="../Assets/c.png" alt="students" height="300" width="250" class="d-block mx-auto">
        </div>
    </section>

    <!--for searching events-->
    <section class="search container-fluid">
    </section>

    <!--for showing events-->
    <section class="events bg-secondary-subtle container-fluid" id="events-section">
        <!-- <input type="date" name="tes" id=""> -->
        <h3 class="text-center my-3">Our available events!</h3>
        <div class="container">
            <div class="row mt-4">
                <?php foreach ($EventList as $array) { ?>
                    <div class="col-12 col-md-6 col-lg-4 col-xxl-3 my-2">
                        <div class="card mx-auto" style="width: 100%;">
                            <img src="../img Event/<?php echo $array["namaGambar"]; ?>" class="card-img-top" alt="<?php echo $array["namaEvent"]; ?>">
                            <div class="card-body">
                                <div class="card-title"><?php echo $array["namaEvent"]; ?></div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="./Js/jquery-3.7.1.min.js"></script>
    <script src="./Js/index.js"></script>
</body>
</html>