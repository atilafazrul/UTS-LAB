<?php

$conn = new mysqli("localhost", "root", "", "mahasiswa_umn");

if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

$sql = "SELECT * FROM mahasiswa";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  echo "<table border='1'>";
  echo "<tr><th>NIM</th><th>Nama</th><th>Prodi</th><th>Foto</th><th>Aksi</th></tr>";

  while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['nim'] . "</td>";
    echo "<td>" . $row['nama'] . "</td>";
    echo "<td>" . $row['prodi'] . "</td>";
    echo "<td><img src='" . $row['foto'] . "' width='100'></td>";
    echo "<td><a href='edit.php?id=" . $row['id'] . "'>Edit</a> | <a href='delete.php?id=" . $row['id'] . "'>Delete</a></td>";
    echo "</tr>";
  }

  echo "</table>";
} else {
  echo "Tidak ada data mahasiswa.";
}

$conn->close();
?>
