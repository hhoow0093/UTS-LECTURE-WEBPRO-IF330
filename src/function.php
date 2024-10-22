<?php
session_start();
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
    $studentEmail = htmlspecialchars(trim($data["emailMahasiswaRegister"]));
    $usernameRegister = htmlspecialchars(trim($data["usernameRegister"]));
    $password = htmlspecialchars(trim($data["PasswordRegister"]));
    $confirmationPassword = htmlspecialchars(trim($data["PasswordRegisterConfirmation"]));
    $role = "student";

    //untuk memastikan pada saat registrasi, format user pada email adalah @student.umn.ac.id

    if (strpos($studentEmail, '@')) {
        $checkEmailArray = explode("@", $studentEmail);
        $acceptedFormat =  "student.umn.ac.id";
        $formatInput = end($checkEmailArray);
        if ($formatInput !== $acceptedFormat) {
            return -6; //berarti emailnya bukan student.
        }
    } else {
        return -5; //masukkan student email yang benar!
    }

    $check = "SELECT * FROM account WHERE email = '$studentEmail'";
    $keranjang = mysqli_query($koneksiDB, $check);
    if (mysqli_num_rows($keranjang) > 0) {
        return -4; // jika ada email yang sama, maka akan error
    }

    if (empty($studentEmail) || empty($usernameRegister) || empty($password) || empty($confirmationPassword)) {
        return -2; //pastikan seluruh data diisi
    }

    if (strlen($usernameRegister) > 10) {
        return -3; //username maksimal 10 kata
    }

    if ($password !== $confirmationPassword) {
        return 1; //salah password
    }

    //membuat tabel baru untuk akun baru
    $usernameEmailRegister = mysqli_real_escape_string($koneksiDB, $studentEmail);


    $perintahAkunBaru = "CREATE TABLE `$usernameEmailRegister` (
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

function loginStudent($data)
{
    global $koneksiDB;
    $emailStudent = htmlspecialchars(trim($data["emailStudent"]));
    $studentPass = htmlspecialchars(trim($data["studentPassword"]));

    if ($emailStudent === "admin@umn.ac.id.com") {
        return -4; //admin tidak boleh login sini
    }

    $perintah = "SELECT * FROM account WHERE email = ?";
    $stmt = mysqli_prepare($koneksiDB, $perintah);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $emailStudent);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($result) === 1) {
                $row = mysqli_fetch_assoc($result);
                $passwordDatabase = $row["password"];

                // Verify password
                if (password_verify($studentPass, $passwordDatabase)) {
                    $_SESSION["login-user"] = true;
                    $_SESSION["user_data"] = $row;
                    return 1; // Login successful
                } else {
                    return 0; // Incorrect password
                }
            } else {
                return -3; // Email not found or multiple entries
            }
        } else {
            return -2; // Execution failed
        }
    } else {
        return -1; // Statement preparation failed
    }
}

function daftarEvent($dataStudent, $namaEvent)
{
    global $koneksiDB;
    $studentEmail = $dataStudent["email"];
    // cek jika melebihi kuota
    $checkKuota = "SELECT maksimum_participant FROM eventlist WHERE namaEvent = '$namaEvent'";
    $resultKuota = mysqli_query($koneksiDB, $checkKuota);
    if ($resultKuota) {
        $rowKuota = mysqli_fetch_assoc($resultKuota);
        $maksimumParticipant = $rowKuota['maksimum_participant'];

        $countParticipants = "SELECT COUNT(*) as total FROM `$namaEvent`";
        $resultCount = mysqli_query($koneksiDB, $countParticipants);
        $rowCount = mysqli_fetch_assoc($resultCount);
        $currentParticipants = $rowCount['total'];

        if ($currentParticipants >= $maksimumParticipant) {
            return -5; // Event quota exceeded
        }
    } else {
        error_log("Error executing quota check query: " . mysqli_error($koneksiDB));
        return -6; // Error in quota check query
    }


    $check = "SELECT * FROM `$namaEvent` WHERE email = '$studentEmail'";
    $checkMaksimumDaftar = "SELECT * FROM `$studentEmail`";
    $keranjangMaksDaftar = mysqli_query($koneksiDB, $checkMaksimumDaftar);
    if (mysqli_num_rows($keranjangMaksDaftar) > 5) {
        return -4; //student tidak boleh daftar event lebih dari 5.
    }
    $keranjang = mysqli_query($koneksiDB, $check);
    if (mysqli_num_rows($keranjang) > 0) {
        return -3; // student tidak boleh daftar event 2 kali.
    }

    $perintah1 = "INSERT INTO `$namaEvent` VALUES ('', '$studentEmail')";
    $perintah2 = "INSERT INTO `$studentEmail` VALUES('', '$namaEvent')";

    if (!mysqli_query($koneksiDB, $perintah1)) {
        error_log("Error executing query 1: " . mysqli_error($koneksiDB));
        return -1; // gagal
    }


    if (!mysqli_query($koneksiDB, $perintah2)) {
        error_log("Error executing query 2: " . mysqli_error($koneksiDB));
        return -2; // gagal
    }

    return 1; // sukses
}

function hapusEvent($id, $emailStudent, $namaEvent)
{
    global $koneksiDB;

    $id = (int)$id;
    $emailStudent = mysqli_real_escape_string($koneksiDB, $emailStudent);
    $namaEvent = mysqli_real_escape_string($koneksiDB, $namaEvent);

    $perintah = "DELETE FROM `$emailStudent` WHERE id = $id";
    $perintah2 = "DELETE FROM `$namaEvent` WHERE email = '$emailStudent'";

    if (mysqli_query($koneksiDB, $perintah)) {
        if (mysqli_query($koneksiDB, $perintah2)) {
            return 1; // success
        } else {
            return -2; // failure on second query
        }
    } else {
        return -1; // failure on first query
    }
}

function editMahasiswaUser($data, $idSISWA)
{
    global $koneksiDB;
    // $emailMahasiswa = $data["EmailStudent"];
    $username = mysqli_real_escape_string($koneksiDB, $data["userNamesiswa"]);
    $password = $data["PasswordSiswa"];

    if ($password === "") {
        $perintah = "UPDATE account SET username = '$username' WHERE id = $idSISWA";
    } else {
        $hashedPass = password_hash($password, PASSWORD_BCRYPT);
        $hashedPass = mysqli_real_escape_string($koneksiDB, $hashedPass);
        $perintah = "UPDATE account SET 
            username = '$username',
            password = '$hashedPass'
            WHERE id = $idSISWA";
    }

    if (mysqli_query($koneksiDB, $perintah)) {
        return 1;
    } else {
        error_log("Error executing query: " . mysqli_error($koneksiDB));
        return 0;
    }
}

function loginAdmin($data)
{
    // data admin
    // email : admin@umn.ac.id.com
    // password : adminumn123
    global $koneksiDB;
    $AdminEmail = $data["emailAdmin"];
    $AdminPassword = $data["adminPassword"];
    if (empty($AdminEmail) || empty($AdminPassword)) {
        return 0; //tidak boleh kosong
    }
    $perintah = "SELECT * FROM account WHERE email = ?";
    $stmt = $koneksiDB->prepare("$perintah");
    $stmt->bind_param("s", $AdminEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    //data admin ketemu
    if (mysqli_num_rows($result) === 1) {
        $row = $result->fetch_assoc();
        $passwordAdminDataBase = $row["password"];

        if (password_verify($AdminPassword, $passwordAdminDataBase)) {
            $_SESSION["login-admin"] = true;
            $_SESSION["data-admin"] = $row;
            return 1;
        } else {
            return -1; // password salah

        }
    } else {
        return -1; // data admin tidak ketemu
    }
}

function getJumlahPendaftar($namaEvent)
{
    global $koneksiDB;
    $countParticipants = "SELECT COUNT(*) as total FROM `$namaEvent`";
    $result = mysqli_query($koneksiDB, $countParticipants);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['total'];
    } else {
        return 0; // gak ada yang daftar
    }
}

function addEvent($data)
{
    global $koneksiDB;
    define('BASE_DIR', dirname(__FILE__) . '/../img Event/');

    $eventName = $data["NamaEvent"];
    $tanggalEvent = $data["EventDate"];
    $waktu = $data["Time"];
    $lokasi = $data["Location"];
    $jumlahKuota = $data["participants"];
    $deskripsi = $data["description"];

    $fotoError = $_FILES["Picture"]["error"];
    $namaFoto = $_FILES["Picture"]["name"];
    $tempName = $_FILES["Picture"]["tmp_name"];

    $ekstensiFotoValid = ["jpg", "jpeg", "png"];
    $ekstensiFoto = explode(".", $namaFoto);
    $ekstensiFoto = strtolower(end($ekstensiFoto));

    // cek jika ada tabel yang sama, jika sama berarti sudah ada eventnya
    $checkTableQuery = "SHOW TABLES LIKE '$eventName'";
    $result = mysqli_query($koneksiDB, $checkTableQuery);

    $flag = false;

    if (mysqli_num_rows($result) == 0) {
        $perintahTabel = "CREATE TABLE `$eventName` (
                            id INT PRIMARY KEY AUTO_INCREMENT,
                            email VARCHAR(100)
                          )";
        if (mysqli_query($koneksiDB, $perintahTabel)) {
            $flag = true; //bisa lanjut proses pembuatan event
        } else {
            $flag = false; // tidak bisa lanjut
        }
    } else {
        $flag = false; // tidak bisa lanjut
    }

    if ($flag) {
        // cek jika form kosong
        if (empty($eventName) || empty($tanggalEvent) || empty($waktu) || empty($lokasi) || empty($jumlahKuota) || empty($deskripsi) || $fotoError === 4) {
            return 0; // artinya form belum diisi dengan lengkap
        } else if (!in_array($ekstensiFoto, $ekstensiFotoValid)) {
            return -1; //ekstensi foto yang salah
        } else {
            // Ensure unique photo name
            $namaFoto = uniqid() . "." . $ekstensiFoto;
            $perintah = "INSERT INTO eventlist (namaEvent, tanggalEvent, description, namaGambar, waktu, lokasi, maksimum_participant) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $koneksiDB->prepare($perintah);
            if ($stmt === false) {
                // Handle error in preparing the statement
                return -2;
            }
            $stmt->bind_param("sssssss", $eventName, $tanggalEvent, $deskripsi, $namaFoto, $waktu, $lokasi, $jumlahKuota);
            $executeResult = $stmt->execute();
            if ($executeResult === false) {
                // Handle error in executing the statement
                return -3;
            }

            $imgDirectory = BASE_DIR;
            if (!move_uploaded_file($tempName, $imgDirectory . $namaFoto)) {
                // Handle error in moving the uploaded file
                return -4;
            }
            return 1;
        }
    }else{
        return -5;
    }
}

// untuk cari tabel pada database

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


// untuk rename table pada database
// function renameTable($oldTableName, $newTableName, $koneksiDB) {
//     // Sanitize table names to prevent SQL injection
//     $oldTableName = mysqli_real_escape_string($koneksiDB, $oldTableName);
//     $newTableName = mysqli_real_escape_string($koneksiDB, $newTableName);

//     // Prepare the RENAME TABLE query
//     $query = "RENAME TABLE `$oldTableName` TO `$newTableName`";

//     // Execute the query
//     if (mysqli_query($koneksiDB, $query)) {
//         echo "Table renamed successfully from '$oldTableName' to '$newTableName'.";
//     } else {
//         echo "Error renaming table: " . mysqli_error($koneksiDB);
//     }
// }

// // Example usage
// $oldTableName = 'old_users';
// $newTableName = 'new_users';
// renameTable($oldTableName, $newTableName, $koneksiDB);
