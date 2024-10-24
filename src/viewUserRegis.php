<?php
// var_dump($_GET["email"]);
require "./function.php";

$email = $_GET["email"];
$perintah = "SELECT * FROM `$email`";
$data = getDATA($perintah);
$arrayNumerikEmail = [];
foreach ($data as $array) {
    $arrayNumerikEmail[] = $array["history"];
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Rgistrant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./styles/adminStyles.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css">
    <style>
        body {
            background-image: url('../Assets/a.jpg');
        }

        body,
        html {
            overflow: hidden;
        }

        @media (max-width: 768px) {
            .table-responsive-container {
                overflow: auto;
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
                        <a class="nav-link disabled mx-2 text-white" aria-current="page" href="#"><?php echo $email; ?></a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link mx-2 text-white" aria-current="page" href="./indexAdmin.php">Event Management</a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link mx-2 text-white" aria-current="page" href="./UserManagement.php">User Management</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>



    <!--Tabel untuk melihat event-->
    <div class="row justify-content-center" id="tabelyes">
        <div class="col-12 col-md-6 col-lg-4">
            <section class="container bg-light p-3 mt-3 rounded">
                <div class="table-responsive-container">
                    <table id="example" class="display table-responsive" style="width:100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>History</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $counter = 1; ?>
                            <?php foreach ($arrayNumerikEmail as $item) { ?>
                                <tr>
                                    <td><?php echo $counter; ?></td>
                                    <td><?php echo $item; ?></td>
                                </tr>
                                <?php $counter++; ?>
                            <?php } ?>
                            <?php $counter = 0; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>No.</th>
                                <th>History</th>
                            </tr>
                        </tfoot>
                    </table>

                </div>

            </section>

        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="./Js/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                responsive: true,
                lengthMenu: [3],
            });

        });
    </script>
</body>

</html>