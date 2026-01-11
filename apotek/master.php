<?php
session_start();
include "koneksi.php"; 

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$menu = $_GET['menu'] ?? '';
$level = $_SESSION['level'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sistem Apotek</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <div style="float: right;">
        Halo, <b><?= $_SESSION['nama'] ?></b> (<?= ucfirst($level) ?>) | 
        <a href="logout.php" style="color: red;">Logout</a>
    </div>
    <h1>SISTEM APOTEK</h1>

    <div class="menu">
        <?php if ($level == 'admin'): ?>
            <a href="apoteker">Apoteker</a> |
        <?php endif; ?>

        <a href="obat">Obat</a> 

        <?php if ($level == 'admin'): ?>
            | <a href="transaksi">Transaksi</a> |
            <a href="riwayat">Riwayat Transaksi</a>
        <?php endif; ?>
    </div>
    <hr>

    <?php
    if ($menu == "apoteker" && $level == 'admin') {
        include "apoteker.php"; 
    } elseif ($menu == "obat") {
        include "obat.php";
    } elseif ($menu == "obat_edit" && $level == 'admin') {
        include "obat_edit.php";
    } elseif ($menu == "transaksi" && $level == 'admin') {
        include "transaksi.php";
    } elseif ($menu == "riwayat" && $level == 'admin') {
        include "transaksi_detail.php";
    } elseif ($menu == "") {
        echo "<h3>Selamat Datang, " . $_SESSION['nama'] . ". Silakan pilih menu.</h3>";
    } else {
        echo "<h3 style='color:red;'>Akses Ditolak! Anda tidak memiliki izin.</h3>";
    }
    ?>
</div>
</body>
</html>