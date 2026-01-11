<?php
$level = $_SESSION['level']; 

if (isset($_POST['simpan']) && $level == 'admin') {
    $nama  = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok  = $_POST['stok'];

    mysqli_query($conn, "
        INSERT INTO obat (nama_obat, harga, stok)
        VALUES ('$nama', '$harga', '$stok')
    ");
    echo "<script>window.location='master.php?menu=obat';</script>";
    exit;
}
?>

<h2>Data Obat</h2>

<?php if ($level == 'admin'): ?>
    <form method="post">
        <input type="text" name="nama" placeholder="Nama Obat" required>
        <input type="number" name="harga" placeholder="Harga" required>
        <input type="number" name="stok" placeholder="Stok" required>
        <button name="simpan">Simpan</button>
    </form>
<?php else: ?>
    <p style="color: #c2185b; font-style: italic;">* Anda hanya memiliki akses untuk melihat data.</p>
<?php endif; ?>

<br>

<table>
    <tr>
        <th>No</th>
        <th>Nama Obat</th>
        <th>Harga</th>
        <th>Stok</th>
        <?php if ($level == 'admin'): ?>
            <th>Aksi</th>
        <?php endif; ?>
    </tr>

<?php
$no = 1;
$data = mysqli_query($conn, "SELECT * FROM obat ORDER BY id_obat DESC");
while ($row = mysqli_fetch_assoc($data)) {
?>
    <tr>
        <td><?= $no++ ?></td>
        <td><?= $row['nama_obat'] ?></td>
        <td><?= $row['harga'] ?></td>
        <td><?= $row['stok'] ?></td>
        
        <?php if ($level == 'admin'): ?>
            <td>
                <a href="master.php?menu=obat_edit&id=<?= $row['id_obat'] ?>">
                    Edit
                </a>
            </td>
        <?php endif; ?>
    </tr>
<?php } ?>
</table>