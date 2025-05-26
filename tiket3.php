<?php
// Koneksi ke database
$koneksi = new mysqli("localhost", "root", "", "tiket");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Delete pemesanan jika diminta
if (isset($_GET['delete'])) {
    $id_hapus = $_GET['delete'];
    $koneksi->query("DELETE FROM pemesanan WHERE id_penumpang='$id_hapus'");
    header("Location: ?view=1");
    exit();
}

// Update data jika form edit dikirim
if (isset($_POST['update'])) {
    $id = $_POST['id_penumpang'];
    $nama = $_POST['nama'];
    $kontak = $_POST['kontak'];
    $tanggal = $_POST['tanggal'];
    $waktu = $_POST['waktu'];
    $rute = $_POST['rute'];
    $jumlah = $_POST['jumlah'];

    $stmt = $koneksi->prepare("UPDATE pemesanan SET nama=?, kontak=?, tanggal=?, waktu=?, rute=?, jumlah=? WHERE id_penumpang=?");
    $stmt->bind_param("sssssis", $nama, $kontak, $tanggal, $waktu, $rute, $jumlah, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: ?view=1");
    exit();
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
            background: linear-gradient(to right, #74ebd5, #ACB6E5);
            margin: 0;
            padding: 40px;
        }
        .form-container, .table-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            max-width: 800px;
            margin: auto;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
        }
        input[type="text"], input[type="date"], input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        .submit-btn, .action-btn {
            margin-top: 20px;
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .submit-btn {
            background-color: #4CAF50;
            color: white;
            width: 100%;
        }
        .submit-btn:hover {
            background-color: #43a047;
        }
        .action-btn {
            background-color: #f44336;
            color: white;
            text-decoration: none;
            margin-right: 5px;
            display: inline-block;
        }
        .action-btn.edit {
            background-color: #2196F3;
        }
        .back-btn {
            display: block;
            margin-top: 20px;
            text-align: center;
            font-weight: bold;
            color: #555;
            text-decoration: none;
            font-size: 14px;
        }
        .back-btn:hover {
            color: #2196F3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
            font-size: 14px;
        }
        th {
            background-color: #f2f2f2;
        }
        /* Styling untuk e-tiket */
        .eticket {
            background: linear-gradient(135deg, #2196F3, #21CBF3);
            color: white;
            border-radius: 15px;
            padding: 25px 30px;
            max-width: 400px;
            margin: 25px auto 0;
            box-shadow: 0 12px 20px rgba(33, 203, 243, 0.6);
            font-size: 16px;
            font-weight: 600;
            line-height: 1.5;
        }
        .eticket h3 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }
        .eticket p {
            margin: 6px 0;
        }
        .eticket p strong {
            display: inline-block;
            width: 140px;
            font-weight: 700;
        }
    </style>
</head>
<body>
<?php if (isset($_GET['view'])): ?>
    <div class="table-container">
        <h2>Daftar Pemesanan Tiket</h2>
        <table>
            <tr>
                <th>ID</th><th>Nama</th><th>Kontak</th><th>Tanggal</th><th>Waktu</th><th>Rute</th><th>Jumlah</th><th>Aksi</th>
            </tr>
            <?php
            $result = $koneksi->query("SELECT * FROM pemesanan");
            while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id_penumpang']) ?></td>
                    <td><?= htmlspecialchars($row['nama']) ?></td>
                    <td><?= htmlspecialchars($row['kontak']) ?></td>
                    <td><?= htmlspecialchars($row['tanggal']) ?></td>
                    <td><?= htmlspecialchars($row['waktu']) ?></td>
                    <td><?= htmlspecialchars($row['rute']) ?></td>
                    <td><?= htmlspecialchars($row['jumlah']) ?></td>
                    <td>
                        <a href="?edit=<?= urlencode($row['id_penumpang']) ?>" class="action-btn edit">Edit</a>
                        <a href="?delete=<?= urlencode($row['id_penumpang']) ?>" class="action-btn" onclick="return confirm('Hapus data ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
        <a href="?" class="back-btn">← Kembali ke Form Pemesanan</a>
    </div>
<?php elseif (isset($_GET['edit'])): 
    $id_edit = $_GET['edit'];
    $result = $koneksi->query("SELECT * FROM pemesanan WHERE id_penumpang='$id_edit'");
    $data = $result->fetch_assoc();
?>
    <div class="form-container">
        <h2>Edit Data Pemesanan</h2>
        <form method="POST">
            <input type="hidden" name="id_penumpang" value="<?= htmlspecialchars($data['id_penumpang']) ?>">
            <label>Nama:</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" required>
            <label>Kontak:</label>
            <input type="text" name="kontak" value="<?= htmlspecialchars($data['kontak']) ?>" required>
            <label>Tanggal:</label>
            <select name="id" required>
                        <option value="">Pilih Jam</option>
                        <?php while ($jenis = $jenis_result->fetch_assoc()) { ?>
                            <option value="<?= $jenis['id'] ?>"><?= $jenis['jam_keberangkatan'] ?></option>
                        <?php } ?>
                    </select>
            <input type="date" name="tanggal" value="<?= htmlspecialchars($data['tanggal']) ?>" required>
            <label>Waktu:</label>
            <input type="text" name="waktu" value="<?= htmlspecialchars($data['waktu']) ?>" required>
            <label>Rute:</label>
            <input type="text" name="rute" value="<?= htmlspecialchars($data['rute']) ?>" required>
            <label>Jumlah Penumpang:</label>
            <input type="number" name="jumlah" value="<?= htmlspecialchars($data['jumlah']) ?>" required>
            <button type="submit" name="update" class="submit-btn">Update</button>
        </form>
        <a href="?view=1" class="back-btn">← Kembali</a>
    </div>
<?php else: ?>
    <div class="form-container">
        <h2>Silakan isi form pemesanan tiket atau <a href="?view=1">lihat daftar</a></h2>
        <form method="POST">
        <?php
            // Variabel untuk menyimpan data e-tiket
            $eticket = null;

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['update'])) {
                $rute = htmlspecialchars($_POST['rute']);
                $waktu = htmlspecialchars($_POST['waktu']);
                $nama = htmlspecialchars($_POST['nama']);
                $kontak = htmlspecialchars($_POST['kontak']);
                $tanggal = htmlspecialchars($_POST['tanggal']);
                $jumlah = (int)$_POST['jumlah'];
                $id_penumpang = uniqid('ID-');
                $kursi = rand(1, 20) . 'A';

                $stmt = $koneksi->prepare("INSERT INTO pemesanan (id_penumpang, nama, kontak, tanggal, waktu, rute, jumlah, kursi) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssis", $id_penumpang, $nama, $kontak, $tanggal, $waktu, $rute, $jumlah, $kursi);
                $stmt->execute();
                $stmt->close();

                // Simpan data tiket ke variabel untuk ditampilkan sebagai e-tiket
                $eticket = [
                    'id_penumpang' => $id_penumpang,
                    'nama' => $nama,
                    'kontak' => $kontak,
                    'tanggal' => $tanggal,
                    'waktu' => $waktu,
                    'rute' => $rute,
                    'jumlah' => $jumlah,
                    'kursi' => $kursi
                ];
            }
        ?>
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

        <?php if ($eticket): ?>
            <div class="eticket" tabindex="0" aria-label="E-Tiket Kereta Api">
                <h3>E-Tiket Kereta Api</h3>
                <p><strong>ID Pemesanan:</strong> <?= htmlspecialchars($eticket['id_penumpang']) ?></p>
                <p><strong>Nama:</strong> <?= htmlspecialchars($eticket['nama']) ?></p>
                <p><strong>Kontak:</strong> <?= htmlspecialchars($eticket['kontak']) ?></p>
                <p><strong>Tanggal:</strong> <?= htmlspecialchars($eticket['tanggal']) ?></p>
                <p><strong>Waktu:</strong> <?= htmlspecialchars($eticket['waktu']) ?></p>
                <p><strong>Rute:</strong> <?= htmlspecialchars($eticket['rute']) ?></p>
                <p><strong>Jumlah Penumpang:</strong> <?= htmlspecialchars($eticket['jumlah']) ?></p>
                <p><strong>Nomor Kursi:</strong> <?= htmlspecialchars($eticket['kursi']) ?></p>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>
</body>
</html>
