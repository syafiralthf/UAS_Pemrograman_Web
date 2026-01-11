<?php
include "koneksi.php";

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    mysqli_query($conn, "DELETE FROM detail_transaksi WHERE id_transaksi='$id'");
    mysqli_query($conn, "DELETE FROM transaksi WHERE id_transaksi='$id'");

    header("Location: master.php?menu=riwayat");
    exit;
}
?>

<h2>Riwayat Transaksi</h2>

<table>
    <tr>
        <th>No</th>
        <th>Tanggal</th>
        <th>Apoteker</th>
        <th>Nama Obat</th>
        <th>Total</th>
        <th>Aksi</th>
    </tr>

<?php
$no = 1;

$q = mysqli_query($conn, "
    SELECT 
        t.id_transaksi,
        t.tanggal,
        a.nama_apoteker,
        GROUP_CONCAT(o.nama_obat SEPARATOR ', ') AS nama_obat,
        SUM(d.jumlah * o.harga) AS total
    FROM transaksi t
    JOIN apoteker a ON t.id_apoteker = a.id_apoteker
    JOIN detail_transaksi d ON t.id_transaksi = d.id_transaksi
    JOIN obat o ON d.id_obat = o.id_obat
    GROUP BY t.id_transaksi
    ORDER BY t.id_transaksi DESC
");

while ($row = mysqli_fetch_assoc($q)) {
?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= $row['tanggal'] ?></td>
    <td><?= $row['nama_apoteker'] ?></td>
    <td><?= $row['nama_obat'] ?></td>
    <td>Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
    <td>
        <a href="master.php?menu=riwayat&hapus=<?= $row['id_transaksi'] ?>"
           onclick="return confirm('Yakin hapus transaksi ini?')">
            Hapus
        </a>
    </td>
</tr>
<?php } ?>
</table>