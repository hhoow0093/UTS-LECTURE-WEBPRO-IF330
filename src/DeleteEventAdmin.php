<?php
require "./function.php";

if (!isset($_SESSION["login-admin"])) {
    header("Location : Loginadmin.php");
    exit();
}

if(empty($_GET)){
    header("Location: indexAdmin.php");
    exit();
}

$id = intval($_GET["id"]); 
$namaEvent  = $_GET["nama"];

$hasil_delete = deleteEvent($id, $namaEvent);
if($hasil_delete === -1){
    echo 
    "
    <script>
        alert('drop tables bermasasalah');
    </script>
    ";
}else if($hasil_delete === -2){
    echo 
    "
    <script>
        alert('delete table pada event list bermasalah');
    </script>
    ";
}else{
    echo 
    "
    <script>
        alert('berhasil di delete!');
    </script>
    ";
}

header("Location: indexAdmin.php");
exit();
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


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="./Js/jquery-3.7.1.min.js"></script>
</body>

</html>