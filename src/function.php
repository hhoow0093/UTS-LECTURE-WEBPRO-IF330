<?php
$koneksiDB = mysqli_connect("localhost", "root", "", "UMNEvent");
function getDATA($perintah){
    global $koneksiDB;
    $data = [];
    $keranjang = mysqli_query($koneksiDB, $perintah);
    while($row = mysqli_fetch_assoc($keranjang)){
        $data[] = $row;
    }
    return $data;
} 

?>