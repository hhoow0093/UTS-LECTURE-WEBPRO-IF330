<?php
require "./function.php";
// var_dump($_GET["email"]); 

//hapus email student pada setiap event tabel
$emailStudent = $_GET["email"];

$perintah = "SELECT history FROM `$emailStudent`";
$result  = mysqli_query($koneksiDB, $perintah);
while($row = mysqli_fetch_assoc($result)){
    $namaEVENT = $row["history"];
    $command = "DELETE FROM `$namaEVENT` WHERE email = '$emailStudent'";
    mysqli_query($koneksiDB, $command);
}

// hapus email pada tabel account
$perintah2 = "DELETE FROM account WHERE email = '$emailStudent'";
mysqli_query($koneksiDB, $perintah2);

//hapus tabel email
$perintah3 = "DROP TABLES `$emailStudent`";
mysqli_query($koneksiDB, $perintah3);

header("Location: UserManagement.php");
exit();
?>