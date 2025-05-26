<?php
// Koneksi ke database
$koneksi = new mysqli("localhost", "root", "", "tiket");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pemesanan Tiket Kereta</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #004080;
            margin: 0;
            padding: 40px;
        }

        .form-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            max-width: 500px;
            margin: auto;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            margin-top: 15px;
            color: #555;
            font-weight: 600;
        }

        input[type="text"],
        input[type="date"],
        input[type="number"],
        select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        .submit-btn {
            margin-top: 25px;
            width: 100%;
            padding: 12px;
            background-color: #004080;
            border: none;
            color: white;
            font-size: 16px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .submit-btn:hover {
            background-color: #4a90e2;
        }

        .back-btn {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #2196F3;
            text-decoration: none;
            font-weight: bold;
        }

        .back-btn:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<?php if ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
    <?php
        $rute = htmlspecialchars($_POST['rute']);
        $waktu = htmlspecialchars($_POST['waktu']);
        $nama = htmlspecialchars($_POST['nama']);
        $kontak = htmlspecialchars($_POST['kontak']);
        $tanggal = htmlspecialchars($_POST['tanggal']);
        $jumlah = htmlspecialchars($_POST['jumlah']);
        $id_penumpang = uniqid('ID-');
        $kursi = rand(1, 20) . 'A';

        // Simpan ke tabel pemesanan
        $stmt = $koneksi->prepare("INSERT INTO pemesanan (id_penumpang, nama, kontak, tanggal, waktu, rute, jumlah, kursi) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssis", $id_penumpang, $nama, $kontak, $tanggal, $waktu, $rute, $jumlah, $kursi);
        $stmt->execute();
        $stmt->close();

        // Simpan ke tabel penumpang (jika belum ada)
        $stmt2 = $koneksi->prepare("INSERT INTO penumpang (id_penumpang, nama, kontak) VALUES (?, ?, ?)");
        $stmt2->bind_param("sss", $id_penumpang, $nama, $kontak);
        $stmt2->execute();
        $stmt2->close();
    ?>
    <div class="form-container">
        <h2>E-TIKET KERETA API</h2>
        <div class="ticket">
            <p><strong>ID Penumpang:</strong> <?= $id_penumpang ?></p>
            <p><strong>Nama:</strong> <?= $nama ?></p>
            <p><strong>Kontak:</strong> <?= $kontak ?></p>
            <p><strong>Tanggal Keberangkatan:</strong> <?= $tanggal ?></p>
            <p><strong>Waktu Keberangkatan:</strong> <?= $waktu ?></p>
            <p><strong>Rute:</strong> <?= $rute ?></p>
            <p><strong>Nomor Kursi:</strong> <?= $kursi ?></p>
            <p><strong>Jumlah Penumpang:</strong> <?= $jumlah ?></p>
            <p><em>Tunjukkan e-tiket ini kepada petugas saat memasuki stasiun.</em></p>
        </div>
        <a href="?" class="back-btn">← Kembali ke Home</a>
    </div>
<?php else: ?>
    <div class="form-container">
        <h2>Pemesanan Tiket Kereta</h2>
        <form method="POST">
            <label for="nama">Nama Lengkap:</label>
            <input type="text" id="nama" name="nama" required>

            <label for="kontak">No. Kontak:</label>
            <input type="text" id="kontak" name="kontak" required>

            <label for="tanggal">Tanggal Keberangkatan:</label>
            <input type="date" id="tanggal" name="tanggal" required>

            <label for="waktu">Waktu Keberangkatan:</label>
            <input type="text" id="waktu" name="waktu" placeholder="Contoh: 07:00 WIB" required>

            <label for="rute">Rute Perjalanan:</label>
            <input type="text" id="rute" name="rute" placeholder="Contoh: Solo Balapan → Yogyakarta" required>

            <label for="jumlah">Jumlah Penumpang:</label>
            <input type="number" id="jumlah" name="jumlah" min="1" required>

            <button type="submit" class="submit-btn">Pesan</button>
        </form>
        <a href="home.php" class="back-btn">← Kembali ke Home</a>
    </div>
<?php endif; ?>
</body>
</html>
