<?php
session_start();
include "koneksi.php";

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND password='$password'");
    
    if (mysqli_num_rows($query) > 0) {
        $user = mysqli_fetch_assoc($query);
        $_SESSION['admin'] = $user['username'];
        $_SESSION['nama']  = $user['nama_lengkap'];
        $_SESSION['level'] = $user['level'];
        
        header("Location: master.php");
        exit();
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Sistem Apotek</title>
    <link rel="stylesheet" href="style.css"> </head>
<body style="display: flex; justify-content: center; align-items: center; height: 100vh; background: #fff0f6; margin:0;">
    <div class="container" style="width: 300px;">
        <h2 style="text-align: center;">Login Sistem</h2> <?php if(isset($error)): ?>
            <p style="color:red; text-align:center; font-size: 13px;"><?= $error ?></p>
        <?php endif; ?>

        <form method="post">
            <input type="text" name="username" placeholder="Username" style="width: 100%; margin-bottom: 10px; box-sizing: border-box;" required>
            <input type="password" name="password" placeholder="Password" style="width: 100%; margin-bottom: 15px; box-sizing: border-box;" required>
            <button name="login" style="width: 100%;">Masuk</button>
        </form>
    </div>
</body>
</html>