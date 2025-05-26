<?php
session_start();
error_reporting(E_ALL);

// Koneksi ke database
$koneksi = mysqli_connect('localhost', 'root', '', 'tiket');
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['username'] = $username;
        header('Location: home.php');
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Tiket Kereta</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
    }

    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      background: transparent;
    }

    #bg-video {
      position: fixed;
      top: 0;
      left: 0;
      min-width: 100%;
      min-height: 100%;
      object-fit: cover;
      z-index: -1;
      filter: brightness(0.5);
    }

    .login-container:hover {
      transform: translateY(-4px);
      box-shadow: 0 20px 45px rgba(0, 0, 0, 0.6);
    }

    .login-container img {
      width: 60px;
      margin-bottom: 20px;
    }

    .login-container h1 {
      font-size: 24px;
      font-weight: 700;
      margin-bottom: 25px;
      color: #fff;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 14px;
      margin-bottom: 16px;
      border: none;
      border-radius: 12px;
      background: rgba(255, 255, 255, 0.89);
      font-size: 15px;
      color: #333;
    }

    input:focus {
      outline: none;
      box-shadow: 0 0 10px #4a90e2;
    }

    button {
      width: 100%;
      padding: 14px;
      background: linear-gradient(to right, #4a90e2, #0052cc);
      border: none;
      border-radius: 12px;
      font-size: 16px;
      font-weight: bold;
      color: white;
      cursor: pointer;
      transition: 0.3s ease;
    }

    button:hover {
      background: linear-gradient(to right, #357abd, #003f99);
      transform: scale(1.03);
    }

    .error {
      background: rgba(255, 0, 0, 0.75);
      color: white;
      padding: 10px;
      border-radius: 10px;
      margin-bottom: 16px;
      font-weight: 600;
      animation: shake 0.3s ease-in-out;
    }

    @keyframes shake {
      0% { transform: translateX(-5px); }
      50% { transform: translateX(5px); }
      100% { transform: translateX(0); }
    }

    @media (max-width: 420px) {
      .login-container {
        width: 90%;
        padding: 30px 20px;
      }
    }
  </style>
</head>
<body>
  <video autoplay muted loop id="bg-video">
    <source src="video kereta.mp4" type="video/mp4" />
  </video>

  <div class="login-container">
    <img src="https://img.icons8.com/ios-filled/100/train.png" alt="Kereta Icon" />
    <h1>Pemesanan Tiket Kereta</h1>
    <?php if (isset($error)) : ?>
      <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="POST" action="">
      <input type="text" name="username" placeholder="Username" required autocomplete="off" />
      <input type="password" name="password" placeholder="Password" required autocomplete="off" />
      <button type="submit">Masuk</button>
    </form>
  </div>
</body>
</html>

