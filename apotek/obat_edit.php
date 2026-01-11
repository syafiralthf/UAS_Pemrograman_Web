<?php
include "koneksi.php";

$id = $_GET['id'];

$q = mysqli_query($conn, "SELECT * FROM obat WHERE id_obat='$id'");
$data = mysqli_fetch_assoc($q);

if (isset($_POST['update'])) {
    $nama  = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok  = $_POST['stok'];

    mysqli_query($conn, "
        UPDATE obat 
        SET nama_obat='$nama', harga='$harga', stok='$stok'
        WHERE id_obat='$id'
    ");

    header("Location: master.php?menu=obat");
    exit;
}
?>

<h2>Edit Obat</h2>

<form method="post">
    <input type="text" name="nama" value="<?= $data['nama_obat'] ?>" required>
    <input type="number" name="harga" value="<?= $data['harga'] ?>" required>
    <input type="number" name="stok" value="<?= $data['stok'] ?>" required>
    <button name="update">Update</button>
    <a href="master.php?menu=obat">Batal</a>
</form>