<?php
$conn = new mysqli("localhost", "root", "", "mahasiswa_umn");
$id = $_GET['id'];

$nim = $_POST['nim'];
$nama = $_POST['nama'];
$prodi = $_POST['prodi'];
$foto = $_FILES['foto'];

if ($foto['name']) {
  $folder = "upload_photos/";
  $path_foto = $folder . basename($foto["name"]);
  move_uploaded_file($foto["tmp_name"], $path_foto);
  $sql = "UPDATE mahasiswa SET nim='$nim', nama='$nama', prodi='$prodi', foto='$path_foto' WHERE id=$id";
} else {
  $sql = "UPDATE mahasiswa SET nim='$nim', nama='$nama', prodi='$prodi' WHERE id=$id";
}

if ($conn->query($sql) === TRUE) {
  echo "Data berhasil diupdate.";
} else {
  echo "Error: " . $conn->error;
}

$conn->close();
?>
