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

$sql = "SELECT * FROM mahasiswa";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Query gagal: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baskervville:ital@0;1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Data Mahasiswa</title>
</head>
<body>
    <div class="container mt-5 text-center"> 
        <h1 class="baskervville-regular-italic">Data Mahasiswa</h1>
        <p>Selamat datang, <?= htmlspecialchars($_SESSION['username']) ?>! <a href="logout.php">Logout</a></p>
        <?php if ($result->num_rows > 0): ?>
            <table class="table table-striped table-bordered mt-3 mx-auto" style="max-width: 800px;"> 
                <thead class="thead-dark">
                    <tr>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Prodi</th>
                        <th>Foto</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nim']) ?></td>
                            <td><?= htmlspecialchars($row['nama']) ?></td>
                            <td><?= htmlspecialchars($row['prodi']) ?></td>
                            <td><img src="<?= htmlspecialchars($row['foto']) ?>" alt="Foto" style="width: 50px; height: auto;"></td>
                            <td>
                                <a class="btn btn-warning" href="edit.php?nim=<?= urlencode($row['nim']) ?>"><i class="fas fa-edit"></i> Edit</a>
                                <a class="btn btn-danger" href="delete.php?nim=<?= urlencode($row['nim']) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');"><i class="fas fa-trash"></i> Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Tidak ada data mahasiswa.</p>
        <?php endif; ?>
        <a class="btn btn-primary" href="form.php">Tambah Mahasiswa</a>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>