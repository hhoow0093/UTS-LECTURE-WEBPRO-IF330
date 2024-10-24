<?php
require "./function.php";
$showModal = false;
// admin@umn.ac.id.com
// adminumn123
if (isset($_POST['loginAdmin'])) {
    // var_dump($_POST);
    $resultLogin = loginAdmin($_POST);
    if($resultLogin === 0){
        $message = "data yang diisi tidak boleh kosong!";
        $showModal = true;
    }
    else if($resultLogin === -1){
        $message = "data yang diisi salah!";
        $showModal = true;
    }
    else if($resultLogin === 1){
        $message = "sukses login!";
        $showModal = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./styles/styles.css">
</head>
<style>
    .thumbnail-login {
        background-image: url('../Assets/a.jpg');
    }

    body {
        margin: 0;
        padding: 0;
    }
</style>

<body>
    <div class="thumbnail-login">
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
                            <a class="nav-link mx-2" href="./index.php">Main page</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!--Form login-->
        <div class="container login-form">
            <div class="row justify-content-center ">
                <div class="col-6 bg-white p-5" id="tampilan-login">
                    <h1 class="mb-5">Admin Login Page</h1>
                    <form class="container-fluid" method="post" action="">
                        <div class="mb-3">
                            <label for="emailAdmin" class="form-label">Admin Email</label>
                            <input type="email" class="form-control" id="emailAdmin" aria-describedby="emailHelp" name="emailAdmin">
                            <div id="emailHelp" class="form-text">masukkan email admin</div>
                        </div>
                        <div class="mb-3">
                            <label for="adminPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="adminPassword" name="adminPassword">
                        </div>
                        <button type="submit" class="btn btn-primary" name="loginAdmin">Login</button>
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

                // Redirect after 5 seconds if registration is successful
                <?php if ($resultLogin === 1) { ?>
                    setTimeout(function() {
                        window.location.href = 'indexAdmin.php';
                    }, 2000);
                <?php } ?>
            <?php } ?>
        });
    </script>
</body>

</html>