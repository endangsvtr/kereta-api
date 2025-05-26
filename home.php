<?php
session_start();
if (!isset($_SESSION['username'])) {
  header('Location: login.php');
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Home - Tiket Kereta</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #2a5298, #1e3c72);
      margin: 0;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      color: white;
    }
    .home-container {
      background: #ffffff22;
      padding: 40px 50px;
      border-radius: 16px;
      box-shadow: 0 8px 30px rgba(0,0,0,0.4);
      width: 90%;
      max-width: 700px;
      text-align: center;
      backdrop-filter: blur(12px);
    }
    h1 {
      margin-bottom: 30px;
      font-weight: 700;
      font-size: 2.5rem;
    }
    .btn-group {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
    }
    a.button {
      background-color: #4a90e2;
      padding: 18px 36px;
      border-radius: 12px;
      text-decoration: none;
      font-size: 18px;
      color: white;
      font-weight: 600;
      transition: background-color 0.3s ease;
      flex: 1 1 180px;
      max-width: 220px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    a.button:hover {
      background-color: #357abd;
    }
  </style>
</head>
<body>
  <div class="home-container">
    <h1>Selamat Datang, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <div class="btn-group">
      <a href="jadwalkereta.php" class="button">Lihat Jadwal Kereta</a>
      <a href="tiket2.php" class="button">Pesan Tiket</a>
      <a href="datapenumpang.php" class="button">Daftar Penumpang</a>
      <a href="logout.php" class="button">Logout</a>
    </div>
  </div>
</body>
</html>
<!-- test -->