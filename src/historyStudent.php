<?php
require "./function.php";
$showModal = false;

if(isset($_SESSION["login-admin"])){
    header("Location: indexAdmin.php");
    exit();
}

if (!isset($_SESSION["login-user"])) {
    header("Location: index.php");
    exit;
} else {
    $dataStudent = $_SESSION["safe_user_data"];
    $studentEmail = $dataStudent["email"];
    $perintah = "SELECT * FROM `$studentEmail`";
    $historyList = getDATA($perintah);
    $counter = 1;

    if (isset($_GET["id"]) && isset($_GET["namaEvent"])) {
        if (hapusEvent($_GET["id"], $studentEmail, $_GET["namaEvent"]) === 1) {
            $_SESSION["message"] = "berhasil di cancel";
            $_SESSION["showModal"] = true;
            echo "<script>document.location.href = 'historyStudent.php';</script>";
        } else {
            $_SESSION["message"] = "query error";
            $_SESSION["showModal"] = true;
            echo "<script>document.location.href = 'historyStudent.php';</script>";
        }
        exit;
    }

    if (isset($_SESSION["showModal"]) && $_SESSION["showModal"] === true) {
        $message = $_SESSION["message"];
        $showModal = true;
        unset($_SESSION["showModal"]);
        unset($_SESSION["message"]);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History <?php echo $dataStudent["username"]; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<link rel="stylesheet" href="./styles/styles.css">
<style>
    .thumbnail {
        background-image: url('../Assets/d.jpg');
        height: 100vh;
    }
</style>

<body>
    <section class="thumbnail container-fluid">
        <nav class="navbar navbar-expand-lg position-absolute top-0 start-0 container-fluid z-2" style="background-color:  rgb(0, 0, 0, 0.1);">
            <div class="container-fluid px-5 py-3">
                <img src="../Assets/b.png" alt="UMN" height="50" width="100" class="mb-3 mb-lg-0">
                <button class="navbar-toggler mb-3 mb-lg-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link mx-2" href="./index.php">Main page</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="table-responsive container-fluid" id="tabel" style="width: 75%;">
            <table class=" table table-dark text-lg-center">
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Redistered event</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($historyList)) { ?>
                        <?php foreach ($historyList as $Array) { ?>
                            <tr class="table-light">
                                <th scope="row" class="table-light">
                                    <div class="row justify-content-center" style="line-height: 2.5em;">
                                        <div class="col-12 col-lg-6">
                                            <?php echo $counter;
                                            $counter++; ?>
                                        </div>
                                    </div>
                                </th>
                                <td class="table-light">
                                    <div class="row justify-content-center" style="line-height: 2.5em;">
                                        <div class="col-12 col-lg-6">
                                            <?php echo $Array["history"]; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="table-light">
                                    <div class="row justify-content-center">
                                        <div class="col-12 col-lg-6">
                                            <a href="./historyStudent.php?id=<?php echo $Array["id"]; ?>&namaEvent=<?php echo $Array["history"]; ?>" onclick="return confirm('Are you sure?');">
                                                <button class="btn btn-outline-danger"> Cancel Registration</button>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr class="table-light">
                            <th scope="row" class="table-light">
                                <div class="row justify-content-center" style="line-height: 2.5em;">
                                    <div class="col-12 col-lg-6">
                                        <?php echo $counter;
                                        ?>
                                    </div>
                                </div>
                            </th>
                            <td class="table-light">
                                <div class="row justify-content-center" style="line-height: 2.5em;">
                                    <div class="col-12 col-lg-6">
                                        none
                                    </div>
                                </div>
                            </td>
                            <td class="table-light">
                                <div class="row justify-content-center">
                                    <div class="col-12 col-lg-6">
                                        none
                                    </div>
                                </div>
                            </td>

                        <?php } ?>
                        <?php $counter = 1; ?>
                </tbody>
            </table>
        </div>
    </section>

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
    <script src="./Js/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            <?php if ($showModal) { ?>
                var myModal = new bootstrap.Modal(document.getElementById('AlertPopup'), {
                    keyboard: false
                });
                var Message = "<?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>";
                $(".modal-di-alert").html(Message);
                myModal.show();
            <?php } ?>
        });
    </script>
</body>

</html>