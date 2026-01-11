<?php

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $jam  = $_POST['jam_kerja'];

    mysqli_query($conn, "
        INSERT INTO apoteker (nama_apoteker, jam_kerja)
        VALUES ('$nama', '$jam')
    ");
    echo "<script>window.location='master.php?menu=apoteker';</script>";
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM apoteker WHERE id_apoteker='$id'");
    echo "<script>window.location='master.php?menu=apoteker';</script>";
}
?>

<h2>Data Apoteker</h2>

<form method="post">
    <input type="text" name="nama" placeholder="Nama apoteker" required>
    <input type="text" name="jam_kerja" placeholder="Jam kerja (08.00 - 16.00)" required>
    <button name="simpan">Simpan</button>
</form>

<br>

<table>
<tr>
    <th>No</th>
    <th>Nama Apoteker</th>
    <th>Jam Kerja</th>
    <th>Aksi</th>
</tr>

<?php
$no = 1;
$data = mysqli_query($conn, "SELECT * FROM apoteker");
while ($row = mysqli_fetch_assoc($data)) {
?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= $row['nama_apoteker'] ?></td>
    <td><?= $row['jam_kerja'] ?></td>
    <td>
        <a href="master.php?menu=apoteker&hapus=<?= $row['id_apoteker'] ?>"
           onclick="return confirm('Yakin hapus apoteker ini?')">
           Hapus
        </a>
    </td>
</tr>
<?php } ?>
</table>