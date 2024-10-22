<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "mahasiswa_umn");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$nim = "";
$nama = "";
$prodi = "";
$currentPhoto = "";

if (isset($_GET['nim'])) {
    $nim = $_GET['nim'];
    $sql = "SELECT * FROM mahasiswa WHERE nim=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nim);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $nim = $data['nim'];
        $nama = $data['nama'];
        $prodi = $data['prodi'];
        $currentPhoto = $data['foto'];
    } else {
        die("Data tidak ditemukan."); 
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css"> 
    <title><?php echo isset($_GET['nim']) ? "Edit Mahasiswa" : "Tambah Mahasiswa"; ?></title>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center"><?php echo isset($_GET['nim']) ? "Edit Mahasiswa" : "Tambah Mahasiswa"; ?></h1>
        <form method="post" enctype="multipart/form-data" action="proses_upload.php"> 
            <div class="form-group">
                <label for="nim">NIM:</label>
                <input type="text" class="form-control" name="nim" id="nim" value="<?php echo $nim; ?>" required <?php echo isset($_GET['nim']) ? "readonly" : ""; ?>>
            </div>
            <div class="form-group">
                <label for="nama">Nama:</label>
                <input type="text" class="form-control" name="nama" id="nama" value="<?php echo $nama; ?>" required>
            </div>
            <div class="form-group">
                <label for="prodi">Prodi:</label>
                <input type="text" class="form-control" name="prodi" id="prodi" value="<?php echo $prodi; ?>" required>
            </div>
            <div class="form-group">
                <label for="foto">Upload Foto:</label>
                <input type="file" class="form-control-file" name="foto" id="foto">
                <?php if ($currentPhoto) { ?>
                    <img src="<?php echo $currentPhoto; ?>" alt="Current Photo" style="width: 100px; height: auto; margin-top: 10px;">
                <?php } ?>
            </div>
            <button type="submit" class="btn btn-success"><?php echo isset($_GET['nim']) ? "Update" : "Tambah"; ?></button>
        </form>
        <a href="index.php" class="btn btn-secondary mt-3">Kembali</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>
