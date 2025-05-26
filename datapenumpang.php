<?php
// Koneksi ke database
$koneksi = new mysqli("localhost", "root", "", "tiket");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Ambil data dari tabel pemesanan
$sql = "SELECT id_penumpang, nama, kontak, kursi, rute, waktu FROM pemesanan";
$result = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Daftar Penumpang - Kereta Api</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f0f4f8;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 1000px;
      margin: 40px auto;
      background-color: #fff;
      padding: 30px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      border-radius: 12px;
    }

    h1 {
      text-align: center;
      color: #333;
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 30px;
    }

    table th, table td {
      padding: 12px 16px;
      border: 1px solid #ccc;
      text-align: left;
    }

    table th {
      background-color: #004080;
      color: white;
    }

    table tbody tr:nth-child(even) {
      background-color: #f2f2f2;
    }

    .btn-home {
      display: inline-block;
      background-color: #004080;
      color: white;
      text-decoration: none;
      padding: 12px 20px;
      border-radius: 8px;
      transition: background-color 0.3s ease;
    }

    .btn-home:hover {
      background-color: #0066cc;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Daftar Penumpang</h1>

    <table>
      <thead>
        <tr>
          <th>No</th>
          <th>ID Penumpang</th>
          <th>Nama Penumpang</th>
          <th>Kontak</th>
          <th>Nomor Kursi</th>
          <th>Rute</th>
          <th>Waktu Keberangkatan</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php $no = 1; ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= htmlspecialchars($row['id_penumpang']) ?></td>
              <td><?= htmlspecialchars($row['nama']) ?></td>
              <td><?= htmlspecialchars($row['kontak']) ?></td>
              <td><?= htmlspecialchars($row['kursi']) ?></td>
              <td><?= htmlspecialchars($row['rute']) ?></td>
              <td><?= htmlspecialchars($row['waktu']) ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="7" style="text-align:center;">Tidak ada data penumpang.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>

    <a href="home.php" class="btn-home">Kembali ke Home</a>
  </div>
</body>
</html>
