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
    // var_dump($_SESSION["safe_user_data"]);
    $dataStudent = $_SESSION["safe_user_data"];
    $studentID = $dataStudent["id"];
    if (isset($_POST["update-user"])) {
        $hasilEdit = editMahasiswaUser($_POST, $studentID);
        if ($hasilEdit === 1) {
            // var_dump($_SESSION["user_data"]);
            unset($_SESSION["user_data"]);
            unset($_SESSION["safe_user_data"]);

            $stmt = $koneksiDB->prepare("SELECT * FROM account WHERE id = ?");
            $stmt->bind_param("i", $studentID); 
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            $_SESSION["user_data"] = $row;

            $userData = $_SESSION["user_data"];
            $safeUserData = [
                'id' => $userData['id'],
                'email' => htmlspecialchars($userData['email'], ENT_QUOTES, 'UTF-8'),
                'username' => htmlspecialchars($userData['username'], ENT_QUOTES, 'UTF-8'),
                'role' => htmlspecialchars($userData['role'], ENT_QUOTES, 'UTF-8')
            ];
            $_SESSION["safe_user_data"] = $safeUserData;
            // var_dump($_SESSION["safe_user_data"]);
            // var_dump($_SESSION["user_data"]);

            $message = "user telah di update!";
            $showModal = true;
        } else if ($hasilEdit === 0) {
            $message = "query error";
            $showModal = true;
        }
        else if ($hasilEdit === -1) {
            $message = "edit batal karena isi form kosong semua";
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
        <div class="container bg-secondary-subtle p-3 rounded" id="edit" style="max-width: 880px; margin: auto;">
            <div class="row align-items-center">
                <div class="col-12 col-md-4 d-flex justify-content-center align-items-center flex-column">
                    <img src="../Assets/default-user.jpg" alt="default user" class="img-fluid">
                    <h3 class="text-center"><?php echo $dataStudent["username"]; ?></h3>
                    <h6 class="text-center"><?php echo $dataStudent["email"]; ?></h6>
                </div>
                <div class="col-12 col-md-8">
                    <div class="container p-3">
                        <form class="my-auto mx-auto" method="post" action="">
                            <div class="mb-3">
                                <label for="EmailStudent" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="EmailStudent" aria-describedby="emailHelp" name="EmailStudent" value="<?php echo $dataStudent["email"]; ?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="userNamesiswa" class="form-label">Username</label>
                                <input type="text" class="form-control" id="userNamesiswa" name="userNamesiswa" value="<?php echo $dataStudent["username"]; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="PasswordSiswa" class="form-label">Password</label>
                                <input type="password" class="form-control" id="PasswordSiswa" name="PasswordSiswa">
                            </div>
                            <div class="d-flex">
                                <button type="submit" class="btn btn-primary ms-auto" name="update-user">Update!</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="./Js/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            <?php if ($showModal) { ?>
                var myModal = new bootstrap.Modal(document.getElementById('AlertPopup'), {
                    keyboard: false
                });
                var Message = "<?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>";
                $(".modal-di-alert").html(Message);
                myModal.show();

                setTimeout(function(){
                    document.location.href = "index.php";

                }, 1500)
            <?php } ?>
        });
    </script>
</body>

</html>