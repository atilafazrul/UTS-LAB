<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "mahasiswa_umn");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $prodi = $_POST['prodi'];
    $foto = $_FILES['foto'];

    $targetDir = "upload_photos/";
    $targetFilePath = $targetDir . basename($foto["name"]);
    if (move_uploaded_file($foto["tmp_name"], $targetFilePath)) {
        $sql = "INSERT INTO mahasiswa (nim, nama, prodi, foto) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE nama=?, prodi=?, foto=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $nim, $nama, $prodi, $targetFilePath, $nama, $prodi, $targetFilePath);

        if ($stmt->execute()) {
        echo "Data berhasil disimpan.";
        header('Location: index.php');
        exit();
    } else {
    echo "Error: " . $stmt->error;
    }
    $stmt->close();
    } else {
    echo "Gagal mengupload foto.";
    }
}

$conn->close();
?>