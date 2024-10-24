<?php
require "./function.php";

if (!isset($_SESSION["login-admin"])) {
    header("Location : Loginadmin.php");
    exit();
}

$showModal = false;
if(empty($_GET)){
    header("Location: indexAdmin.php");
    exit();
}
$id = intval($_GET["id"]);
$perintah = "SELECT * FROM eventlist WHERE id = $id";
$dataEvent = getDATA($perintah);
if (isset($_POST["edit-event"])) {
    $hasilEditEvent = EditEvent($_POST, $id);
    if($hasilEditEvent === 1){
        $message = "berhasil edit";
        $showModal = true;
    }else if($hasilEditEvent === -1){
        $message = "ekstensi foto salah";
        $showModal = true;
    }else if($hasilEditEvent === -2){
        $message = "nama event tidak boleh kosong";
        $showModal = true;
    }else if($hasilEditEvent === -3){
        $message = "prepare bermasalah";
        $showModal = true;
    }else if($hasilEditEvent === -4){
        $message = "rename table bermasalah";
        $showModal = true;
    }else if($hasilEditEvent === -5){
        $message = "execute bermasalah";
        $showModal = true;
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
    <!-- Modal form-->
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" id="isiModal">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="eventDetails">Event Details</h1>
                </div>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="modal-body" id="modalBody">
                        <form>
                            <div class="form-group">
                                <label for="NamaEvent" class="col-form-label">Event Name</label>
                                <input type="text" class="form-control" id="NamaEvent" name="NamaEvent" value="<?php echo $dataEvent[0]["namaEvent"]; ?>">
                                <input type="hidden" class="form-control" id="NamaEvent" name="NamaEventLama" value="<?php echo $dataEvent[0]["namaEvent"]; ?>">
                            </div>
                            <div class="form-group">
                                <label for="EventDate" class="col-form-label">Event Date</label>
                                <input type="date" class="form-control" id="EventDate" name="EventDate" value="<?php echo $dataEvent[0]["tanggalEvent"]; ?>">
                            </div>
                            <div class="form-group">
                                <label for="Time" class="col-form-label">Time</label>
                                <input type="time" class="form-control" id="Time" name="Time" value="<?php echo $dataEvent[0]["waktu"]; ?>">
                            </div>
                            <div class="form-group">
                                <label for="Location" class="col-form-label">Location</label>
                                <input type="text" class="form-control" id="Location" name="Location" value="<?php echo $dataEvent[0]["lokasi"] ?>">
                            </div>
                            <div class="form-group">
                                <label for="participants" class="col-form-label">Maximum Participants</label>
                                <input type="number" class="form-control" id="participants" name="participants" min="1" value="<?php echo intval($dataEvent[0]["maksimum_participant"]); ?>">
                            </div>
                            <div class="form-group">
                                <label for="description" class="col-form-label">Event Description</label>
                                <input type="text" class="form-control" id="description" name="description" value="<?php echo $dataEvent[0]["description"]; ?>">
                            </div>
                            <div class="form-group">
                                <label for="Picture" class="col-form-label">Event Picture</label>
                                <img src="../img Event/<?php echo $dataEvent[0]["namaGambar"]; ?>" alt="" class="img-fluid my-2">
                                <input type="hidden" name="PictureLama" value="<?php echo $dataEvent[0]["namaGambar"]; ?>">
                                <input type="file" class="form-control" id="Picture" name="Picture">
                            </div>
                            <div class="form-group">
                                <button type="submit" name="edit-event" class="btn btn-primary mt-2 ms-auto">Edit Event!</button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <a href="./indexAdmin.php"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button></a>
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
    <script>
        $(document).ready(function() {
            // Show the modal automatically
            var myModal = new bootstrap.Modal(document.getElementById('myModal'), {
                backdrop: 'static',
                keyboard: false
            });
            myModal.show();

            <?php if ($showModal) { ?>
                var myModal = new bootstrap.Modal(document.getElementById('AlertPopup'), {
                    keyboard: false
                });
                var Message = "<?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>";
                $(".modal-di-alert").html(Message);
                myModal.show();
                <?php if ($hasilEditEvent === 1) { ?>
                        window.location.href = 'indexAdmin.php';
                <?php } ?>
            <?php } ?>
        });
    </script>
</body>

</html>