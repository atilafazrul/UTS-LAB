<?php
$conn = new mysqli("localhost", "root", "", "mahasiswa_umn");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['nim'])) {
    $nim = $_GET['nim'];
    $sql = "SELECT * FROM mahasiswa WHERE nim = '$nim'";
    $result = $conn->query($sql);
    $data = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $prodi = $_POST['prodi'];

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "upload_photos/"; 
        $fileName = basename($_FILES["foto"]["name"]);
        $targetFilePath = $targetDir . $fileName;

        move_uploaded_file($_FILES["foto"]["tmp_name"], $targetFilePath);
        
        $sql = "UPDATE mahasiswa SET nama='$nama', prodi='$prodi', foto='$targetFilePath' WHERE nim='$nim'";
    } else {
        
        $sql = "UPDATE mahasiswa SET nama='$nama', prodi='$prodi' WHERE nim='$nim'";
    }

    $conn->query($sql);
    header('Location: index.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css"> 
    <title>Edit Mahasiswa</title>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Edit Mahasiswa</h1>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nim">NIM:</label>
                <input type="text" class="form-control" name="nim" id="nim" value="<?php echo $data['nim']; ?>" required readonly>
            </div>
            <div class="form-group">
                <label for="nama">Nama:</label>
                <input type="text" class="form-control" name="nama" id="nama" value="<?php echo $data['nama']; ?>" required>
            </div>
            <div class="form-group">
                <label for="prodi">Prodi:</label>
                <input type="text" class="form-control" name="prodi" id="prodi" value="<?php echo $data['prodi']; ?>" required>
            </div>
            <div class="form-group">
                <label for="foto">Foto:</label>
                <input type="file" class="form-control-file" name="foto" id="foto">
                <small class="form-text text-muted">Upload a new photo if you want to change it.</small>
                <img src="<?php echo $data['foto']; ?>" alt="Current Photo" style="width: 100px; height: auto; margin-top: 10px;">
            </div>
            <button type="submit" class="btn btn-success">Update</button>
        </form>
        <a href="index.php" class="btn btn-secondary mt-3">Kembali</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>

