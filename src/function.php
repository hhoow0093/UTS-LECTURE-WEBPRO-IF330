<?php
$koneksiDB = mysqli_connect("localhost", "root", "", "UMNEvent");

function getDATA($perintah)
{
    global $koneksiDB;
    $data = [];
    $keranjang = mysqli_query($koneksiDB, $perintah);
    while ($row = mysqli_fetch_assoc($keranjang)) {
        $data[] = $row;
    }
    return $data;
}

function register($data)
{
    global $koneksiDB;
    $studentEmail = trim($data["emailMahasiswaRegister"]);
    $usernameRegister = trim($data["usernameRegister"]);
    $password = trim($data["PasswordRegister"]);
    $confirmationPassword = trim($data["PasswordRegisterConfirmation"]);
    $role = "student";

    $check = "SELECT * FROM account WHERE email = '$studentEmail'";
    $keranjang = mysqli_query($koneksiDB, $check);
    if (mysqli_num_rows($keranjang) > 0) {
        return -4;
    }

    if (empty($studentEmail) || empty($usernameRegister) || empty($password) || empty($confirmationPassword)) {
        return -2;
    }

    if (strlen($usernameRegister) > 10) {
        return -3;
    }

    if ($password !== $confirmationPassword) {
        return 1; //salah password
    }

    //membuat tabel baru untuk akun baru

    $usernameRegisterTabel = mysqli_real_escape_string($koneksiDB, $usernameRegister);

    //buat cari tabel spesifik
    //     SELECT table_name 
    // FROM information_schema.tables 
    // WHERE table_schema = 'your_database_name' 
    // AND table_name = 'users';

    

    $perintahAkunBaru = "CREATE TABLE `$usernameRegisterTabel` (
    id INT PRIMARY KEY AUTO_INCREMENT,
    history VARCHAR(100)
    )";
    mysqli_query($koneksiDB, $perintahAkunBaru);



    //password di hash dulu
    $hashedPass = password_hash($password, PASSWORD_BCRYPT);

    $perintah = "INSERT INTO account (email, username, password, role) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($koneksiDB, $perintah);
    if ($stmt) {

        mysqli_stmt_bind_param($stmt, "ssss", $studentEmail, $usernameRegister, $hashedPass, $role);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return 0; //sukses
        } else {
            // echo "Error: " . mysqli_stmt_error($stmt);
            mysqli_stmt_close($stmt);
            return -1; //gagal
        }
    } else {
        return -1;
    }
}
// $databaseName = 'your_database_name';
// $tableNameToFind = 'users';

// $query = "SELECT table_name 
//           FROM information_schema.tables 
//           WHERE table_schema = ? 
//           AND table_name = ?";

// $stmt = mysqli_prepare($koneksiDB, $query);
// if ($stmt) {
//     mysqli_stmt_bind_param($stmt, "ss", $databaseName, $tableNameToFind);
//     mysqli_stmt_execute($stmt);
//     mysqli_stmt_bind_result($stmt, $tableName);

//     if (mysqli_stmt_fetch($stmt)) {
//         echo "Table '$tableNameToFind' found in database '$databaseName'.";
//     } else {
//         echo "Table '$tableNameToFind' not found in database '$databaseName'.";
//     }

//     mysqli_stmt_close($stmt);
// } else {
//     echo "Error preparing statement: " . mysqli_error($koneksiDB);
// }