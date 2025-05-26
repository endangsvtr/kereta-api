<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "tiket";

$koneksi = mysqli_connect($host, $user, $password, $database);
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Ambil data jadwal kereta dari database
$query_jadwal = "SELECT * FROM keretaapi";
$result_jadwal = mysqli_query($koneksi, $query_jadwal);
$jadwal_keretaapi = [];
while ($row = mysqli_fetch_assoc($result_jadwal)) {
    $jadwal_keretaapi[] = $row;
}

$show_eticket = false;
$tiket = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $kereta_id = $_POST['kereta_id'];
    $tanggal = $_POST['tanggal'];
    $jadwal_kereta = $_POST['jadwal_kereta'];
    $harga = $_POST['harga'];
    $rute = $_POST['rute'];
    $nomor_hp = $_POST['nomor_hp'];

    $query = "INSERT INTO tiket (nama, kereta_id, tanggal, jadwal_kereta, harga, rute, nomor_hp)
              VALUES ('$nama', '$kereta_id', '$tanggal', '$jadwal_kereta', '$harga', '$rute', '$nomor_hp')";
    mysqli_query($koneksi, $query);

    $last_id = mysqli_insert_id($koneksi);

    // Ambil data tiket baru untuk ditampilkan e-tiket
    $query_tiket = "SELECT t.*, k.nama as nama_kereta, k.kelas FROM tiket t
                    LEFT JOIN keretaapi k ON t.kereta_id = k.id
                    WHERE t.id = $last_id LIMIT 1";
    $result_tiket = mysqli_query($koneksi, $query_tiket);
    $tiket = mysqli_fetch_assoc($result_tiket);

    $show_eticket = true;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Pemesanan Tiket Kereta & E-Tiket</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #0a1e4d;
            color: #ffffff;
            margin: 0;
            padding: 40px;
        }
        h2, h1 {
            text-align: center;
            color: #ffffff;
            text-shadow: 1px 1px 4px rgba(0,0,0,0.4);
        }

        /* Form styling */
        form {
            max-width: 600px;
            margin: 20px auto 40px;
            background-color: #123074;
            padding: 25px 30px;
            border-radius: 10px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.4);
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: #ffffff;
        }
        input[type="text"],
        input[type="date"],
        select {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 18px;
            border: 1px solid #4474d9;
            border-radius: 6px;
            background-color: #0a1e4d;
            color: #ffffff;
            font-size: 1em;
            transition: border-color 0.3s ease;
        }
        input:focus,
        select:focus {
            border-color: #a5cdfd;
            outline: none;
            background-color: #142c6b;
        }
        input[readonly] {
            background-color: #1a2f61;
        }
        button {
            width: 100%;
            padding: 12px;
            font-size: 1.1em;
            background-color: #1f3bb3;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #3e57cc;
        }

        /* E-Tiket styling */
        .eticket-container {
            max-width: 600px;
            margin: auto;
            background-color: #123074;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.6);
        }
        .eticket-container .detail {
            font-size: 1.1em;
            margin-bottom: 18px;
        }
        .eticket-container .label {
            font-weight: bold;
            color: #a5cdfd;
            width: 160px;
            display: inline-block;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-style: italic;
            color: #a5cdfd;
            font-size: 0.9em;
        }
        .button-back {
            display: inline-block;
            margin-top: 30px;
            padding: 10px 25px;
            background-color: #1f3bb3;
            border-radius: 8px;
            color: white;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
            cursor: pointer;
            border: none;
        }
        .button-back:hover {
            background-color: #3e57cc;
        }
    </style>

    <script>
    const keretaData = <?= json_encode($jadwal_keretaapi) ?>;

    function updateKeretaInfo() {
        const select = document.querySelector('select[name="kereta_id"]');
        const selectedId = select.value;

        const jadwalInput = document.querySelector('input[name="jadwal_kereta"]');
        const hargaInput = document.querySelector('input[name="harga"]');
        const ruteInput = document.querySelector('input[name="rute"]');

        const kereta = keretaData.find(k => k.id == selectedId);
        if (kereta) {
            jadwalInput.value = kereta.jam_keberangkatan;
            hargaInput.value = kereta.harga;
            ruteInput.value = kereta.rute_perjalanan;
        } else {
            jadwalInput.value = '';
            hargaInput.value = '';
            ruteInput.value = '';
        }
    }

    window.onload = function() {
        updateKeretaInfo();
    }
    </script>
</head>
<body>

<?php if (!$show_eticket): ?>
    <h2>Form Pemesanan Tiket Kereta</h2>
    <form method="POST" action="">
        <label>Nama:</label>
        <input type="text" name="nama" required>

        <label>Nomor HP:</label>
        <input type="text" name="nomor_hp" required pattern="[0-9]+" title="Hanya angka">

        <label>Pilih Kereta:</label>
        <select name="kereta_id" onchange="updateKeretaInfo()" required>
            <option value="">-- Pilih Kereta --</option>
            <?php foreach ($jadwal_keretaapi as $kereta): ?>
                <option value="<?= $kereta['id'] ?>"><?= htmlspecialchars($kereta['nama']) ?> (<?= htmlspecialchars($kereta['kelas']) ?>)</option>
            <?php endforeach; ?>
        </select>

        <label>Jam Keberangkatan:</label>
        <input type="text" name="jadwal_kereta" readonly>

        <label>Harga:</label>
        <input type="text" name="harga" readonly>

        <label>Rute Perjalanan:</label>
        <input type="text" name="rute" readonly>

        <label>Tanggal Keberangkatan:</label>
        <input type="date" name="tanggal" required>

        <button type="submit">Pesan Tiket</button>
    </form>

<?php else: ?>
    <h1>E-Tiket Kereta</h1>
    <div class="eticket-container">
        <div class="detail"><span class="label">Nama Pemesan:</span> <?= htmlspecialchars($tiket['nama']) ?></div>
        <div class="detail"><span class="label">Nomor HP:</span> <?= htmlspecialchars($tiket['nomor_hp']) ?></div>
        <div class="detail"><span class="label">Nama Kereta:</span> <?= htmlspecialchars($tiket['nama_kereta']) ?> (<?= htmlspecialchars($tiket['kelas']) ?>)</div>
        <div class="detail"><span class="label">Jam Keberangkatan:</span> <?= htmlspecialchars($tiket['jadwal_kereta']) ?></div>
        <div class="detail"><span class="label">Rute Perjalanan:</span> <?= htmlspecialchars($tiket['rute']) ?></div>
        <div class="detail"><span class="label">Tanggal Keberangkatan:</span> <?= htmlspecialchars($tiket['tanggal']) ?></div>
        <div class="detail"><span class="label">Harga Tiket:</span> Rp <?= number_format($tiket['harga'], 0, ',', '.') ?></div>

        <div class="footer">
            Terima kasih telah memesan tiket kereta bersama kami.<br>
            Selamat menikmati perjalanan Anda!
        </div>

        <button class="button-back" onclick="window.location='tiket2.php'">Pesan Tiket Lagi</button>
    </div>
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
            <a href="?" class="back-btn">← Kembali ke Menu Utama</a>
        <?php endif; ?>
<?php endif; ?>

</body>
</html>