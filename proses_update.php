<?php
$conn = new mysqli("localhost", "root", "", "mahasiswa_umn");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $prodi = $_POST['prodi'];
    $foto = $_FILES['foto'];


    $updateQuery = "UPDATE mahasiswa SET nama='$nama', prodi='$prodi'";

    if (!empty($foto['name'])) {
        $targetDir = "upload_photos/"; 
        $targetFilePath = $targetDir . basename($foto["name"]);

        if (move_uploaded_file($foto["tmp_name"], $targetFilePath)) {
            $updateQuery .= ", foto='$targetFilePath'"; 
        } else {
            echo "Gagal mengupload foto.";
            exit; 
        }
    }

    $updateQuery .= " WHERE nim='$nim'";

    if ($conn->query($updateQuery) === TRUE) {
        echo "Data berhasil diupdate."; 
        header('Location: index.php'); 
        exit();
    } else {
        echo "Error: " . $conn->error; 
    }
}

$conn->close();
?>

