<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "tiket";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$edit_data = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM keretaapi WHERE id = ?");
    $stmt->bind_param("i", $id); // Assuming id is an integer
    $stmt->execute();
    $result = $stmt->get_result();
    
    if (!$result) {
        die("Query failed: " . $conn->error);
    }
    
    $edit_data = $result->fetch_assoc();
}

// Handle form submission for insert/update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? null;
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    $kapasitas = $_POST['kapasitas'];

    if (isset($_POST['update'])) {
        // Update logic
        $stmt = $conn->prepare("UPDATE keretaapi SET nama=?, kelas=?, kapasitas=? WHERE id=?");
        $stmt->bind_param("ssii", $nama, $kelas, $kapasitas, $id);
        $stmt->execute();
    } else {
        // Insert logic
        $stmt = $conn->prepare("INSERT INTO keretaapi (nama, kelas, kapasitas) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $nama, $kelas, $kapasitas);
        $stmt->execute();
    }
    header("Location: jadwalkereta.php"); // Redirect to avoid form resubmission
    exit();
}

// Fetch all records for display
$result = $conn->query("SELECT * FROM keretaapi");
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Jadwal Kereta Api</title>
    <style>
        /* Reset box sizing */
        *, *::before, *::after {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #0a1e4d;
            color: #ffffff;
            margin: 0;
            padding: 20px;
        }

        center {
            max-width: 900px;
            margin: 0 auto;
        }

        h1 {
            font-size: 2.5em;
            margin-bottom: 20px;
            color: #ffffff;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
        }

        form table {
            width: 100%;
            max-width: 600px;
            background-color: #123074;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }

        form table td {
            padding: 10px;
            vertical-align: middle;
            font-size: 1.1em;
        }

        form input[type="text"],
        form input[type="number"] {
            width: 100%;
            padding: 8px 10px;
            border-radius: 5px;
            border: 1px solid #4474d9;
            background-color: #0a1e4d;
            color: white;
            font-size: 1em;
            transition: border-color 0.3s ease;
        }
        form input[type="text"]:focus,
        form input[type="number"]:focus {
            border-color: #a5cdfd;
            outline: none;
            background-color: #142c6b;
        }

        button[type="submit"] {
            background-color: #1f3bb3;
            color: white;
            font-weight: bold;
            padding: 10px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease;
            margin-right: 15px;
        }
        button[type="submit"]:hover {
            background-color: #3e57cc;
        }

        a {
            color: #a5cdfd;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        a:hover {
            color: #edf4ff;
            text-decoration: underline;
        }

        table.border {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }

        .button {
            width: 100%;
            max-width: 600px;
            background-color: #123074;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }

        table[border="1"] {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            background-color: #123074;
            border-radius: 10px;
            overflow: hidden;
        }

        table[border="1"] th, table[border="1"] td {
            border: 1px solid #1c2f6a;
            padding: 12px 15px;
            text-align: center;
            font-size: 1em;
        }

        table[border="1"] th {
            background-color: #1f3bb3;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #e1e9ff;
        }

        table[border="1"] tr:nth-child(even) {
            background-color: #142c6b;
        }

        table[border="1"] tr:hover {
            background-color: #2a4099;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <center>
        <h1>JADWAL KERETA API</h1>
        <form method="post">
            <table border="1">
                <tr>
                    <th>id kereta</th>
                    <th>Nama kereta</th>
                    <th>Kelas</th>
                    <th>Kapasitas</th>
                    <th>Jam Keberangkatan</th>
                    <th>Rute Perjalanan</th>
                    <th>Harga</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['nama'] ?></td>
                    <td><?= $row['kelas'] ?></td>
                    <td><?= $row['kapasitas'] ?></td>
                    <td><?= $row['jam_keberangkatan'] ?></td>
                    <td><?= $row['rute_perjalanan'] ?></td>
                    <td><?= $row['harga'] ?></td>
                </tr>
                <?php } ?>
            </table>
            <p align="right"> <a href="home.php" class="button">Back</a>
        </form>
    </center>
</body>
</html>

