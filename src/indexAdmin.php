<?php
require "./function.php";

// biar folder foto dah terlalu penuh
$daftarFotoDB = getDATA("SELECT namaGambar FROM eventlist");
$listNumerikFotoDB = [];
foreach ($daftarFotoDB as $item) {
    $listNumerikFotoDB[] = $item["namaGambar"];
}
$imgDirectory = BASE_DIR;
$listIMGFolder = [];
$files = scandir($imgDirectory);
foreach ($files as $file) {
    $listIMGFolder[] = $file;
}

foreach ($listIMGFolder as $item) {
    if ($item === "." || $item === "..") {
        continue;
    } else {
        if (!in_array($item,  $listNumerikFotoDB)) {
            unlink($imgDirectory . $item);
        }
    }
}

$showModal = false;
if (!isset($_SESSION["login-admin"])) {
    header("Location : Loginadmin.php");
    exit();
} else if (isset($_SESSION["login-admin"])) {
    $dataAdmin = $_SESSION["data-admin"];
    $safeDataAdmin =
        [
            "email" => $dataAdmin["email"],
            "username" => $dataAdmin["username"]
        ];

    $perintah = "SELECT * FROM eventlist";
    $listEvent = getDATA($perintah);
    // var_dump($listEvent);
    if (isset($_POST["buat-event"])) {
        $hasilAddEvent = addEvent($_POST);
        if ($hasilAddEvent === 0) {
            $message = "masukkan data yang lengkap";
            $showModal = true;
        } else if ($hasilAddEvent === -1) {
            $message = "file ekstensi salah!";
            $showModal = true;
        } else if ($hasilAddEvent === -2) {
            $message = "prepare statement bermasalah!";
            $showModal = true;
        } else if ($hasilAddEvent === -3) {
            $message = "execute statement bermasalah!";
            $showModal = true;
        } else if ($hasilAddEvent === -4) {
            $message = "uploading file bermasalah!";
            $showModal = true;
        } else if ($hasilAddEvent === -5) {
            $message = "tidak boleh buat event yang sama";
            $showModal = true;
        } else if ($hasilAddEvent === 1) {
            $message = "sukses";
            $showModal = true;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./styles/adminStyles.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css">
    <style>
        body {
            background-image: url('../Assets/a.jpg');
        }

        @media (max-width: 768px) {
            .table-responsive-container {
                overflow: auto;
            }

            .dt-layout-row {
                display: flex;
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <!-- navbar-->
    <nav class="navbar navbar-expand-lg position-relative container-fluid " style="background-color:  rgb(0, 0, 0, 0.1);">
        <div class="container-fluid px-5 py-3">
            <img src="../Assets/b.png" alt="UMN" height="50" width="100" class="mb-3 mb-lg-0">
            <button class="navbar-toggler mb-3 mb-lg-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link mx-2 text-white" aria-current="page" href="./indexAdmin.php">Event Management</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mx-2 text-white" href="./UserManagement.php">User Management</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mx-2 text-white" href="./logout.php">Log out</a>
                    </li>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!--Tombol buat event-->
    <section class="container">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">Buat Event!</button>
    </section>

    <!--Tabel untuk melihat event-->
    <section class="container bg-light p-3 mt-3 rounded">
        <div class="table-responsive-container">
            <table id="example" class="display table-responsive" style="width:100%">
                <thead>
                    <tr>
                        <th>Nama Event</th>
                        <th>Jumlah Pendaftar Event</th>
                        <th>Maskimum pendaftaran</th>
                        <th>Edit Event</th>
                        <th>Delete Event</th>
                        <th>Registrant list</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listEvent as $arr) { ?>
                        <?php
                        $jumlahPendaftar = getJumlahPendaftar($arr["namaEvent"]);
                        ?>
                        <tr>
                            <td><?php echo $arr["namaEvent"]; ?></td>
                            <td><?php echo $jumlahPendaftar; ?></td>
                            <td><?php echo $arr["maksimum_participant"]; ?></td>
                            <td>
                                <a href="./EditEventAdmin.php?id=<?php echo $arr["id"];?>"><button type="button" class="btn btn-outline-primary">Edit Event</button></a>

                            </td>
                            <td>
                                <a href="./DeleteEventAdmin.php?id=<?php echo $arr["id"];?>&nama=<?php echo $arr["namaEvent"];?>"><button type="button" class="btn btn-outline-danger" onclick="return confirm('are you sure to delete?');">Delete Event</button></a>
                            </td>
                            <td>
                                <a href="./convertXL.php?id=<?php echo $arr["id"];?>&nama=<?php echo $arr["namaEvent"];?>"><button type="button" class="btn btn-outline-success">Download registrant</button></a>
                                <a href="./viewRegis.php?id=<?php echo $arr["id"];?>&nama=<?php echo $arr["namaEvent"];?>"><button type="button" class="btn btn-outline-info">Check Registrant</button></a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Nama Event</th>
                        <th>Jumlah Pendaftar Event</th>
                        <th>Maskimum pendaftaran</th>
                        <th>Edit Event</th>
                        <th>Delete Event</th>
                        <th>Registrant list</th>
                    </tr>
                </tfoot>
            </table>

        </div>

    </section>

    <!-- Modal form-->
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" id="isiModal">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="eventDetails">Event Details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="modal-body" id="modalBody">
                        <form>
                            <div class="form-group">
                                <label for="NamaEvent" class="col-form-label">Event Name</label>
                                <input type="text" class="form-control" id="NamaEvent" name="NamaEvent">
                            </div>
                            <div class="form-group">
                                <label for="EventDate" class="col-form-label">Event Date</label>
                                <input type="date" class="form-control" id="EventDate" name="EventDate">
                            </div>
                            <div class="form-group">
                                <label for="Time" class="col-form-label">Time</label>
                                <input type="time" class="form-control" id="Time" name="Time">
                            </div>
                            <div class="form-group">
                                <label for="Location" class="col-form-label">Location</label>
                                <input type="text" class="form-control" id="Location" name="Location">
                            </div>
                            <div class="form-group">
                                <label for="participants" class="col-form-label">Maximum Participants</label>
                                <input type="number" class="form-control" id="participants" name="participants" min="1">
                            </div>
                            <div class="form-group">
                                <label for="description" class="col-form-label">Event Description</label>
                                <textarea class="form-control" id="description" name="description"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="Picture" class="col-form-label">Event Picture</label>
                                <input type="file" class="form-control" id="Picture" name="Picture">
                            </div>
                            <div class="form-group">
                                <button type="submit" name="buat-event" class="btn btn-primary mt-2 ms-auto">Create New Event!</button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
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
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                responsive: true,
                lengthMenu: [3, 5, 10],
            });
            <?php if ($showModal) { ?>
                var myModal = new bootstrap.Modal(document.getElementById('AlertPopup'), {
                    keyboard: false
                });
                var Message = "<?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>";
                $(".modal-di-alert").html(Message);
                myModal.show();
                <?php if ($hasilAddEvent === 1) { ?>
                    setTimeout(function() {
                        window.location.href = 'indexAdmin.php';
                    }, 2000);
                <?php } ?>
            <?php } ?>

        });
    </script>
</body>

</html>