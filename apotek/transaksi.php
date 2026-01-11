<?php
include "koneksi.php";

if (isset($_POST['simpan'])) {
    $id_apoteker = $_POST['apoteker'];
    $id_obat     = $_POST['obat'];
    $jumlah      = $_POST['jumlah'];

    $q = mysqli_query($conn, "SELECT harga, stok FROM obat WHERE id_obat='$id_obat'");
    $obat = mysqli_fetch_assoc($q);

    if ($jumlah > $obat['stok']) {
        echo "<script>alert('Stok tidak mencukupi');</script>";
    } else {

        $subtotal = $obat['harga'] * $jumlah;

        mysqli_query($conn, "
            INSERT INTO transaksi (id_apoteker, tanggal)
            VALUES ('$id_apoteker', NOW())
        ");
        $id_transaksi = mysqli_insert_id($conn);

        mysqli_query($conn, "
            INSERT INTO detail_transaksi (id_transaksi, id_obat, jumlah)
            VALUES ('$id_transaksi', '$id_obat', '$jumlah')
        ");

        mysqli_query($conn, "
            UPDATE obat 
            SET stok = stok - $jumlah 
            WHERE id_obat='$id_obat'
        ");

        header("Location: master.php?menu=riwayat");
        exit;
    }
}
?>

<h2>Transaksi Penjualan</h2>

<form method="post">
    <select name="apoteker" required>
        <option value="">-- Pilih Apoteker --</option>
        <?php
        $a = mysqli_query($conn, "SELECT * FROM apoteker");
        while ($row = mysqli_fetch_assoc($a)) {
            echo "<option value='$row[id_apoteker]'>$row[nama_apoteker]</option>";
        }
        ?>
    </select>

    <select name="obat" required>
        <option value="">-- Pilih Obat --</option>
        <?php
        $o = mysqli_query($conn, "SELECT * FROM obat");
        while ($row = mysqli_fetch_assoc($o)) {
            echo "<option value='$row[id_obat]'>
                    $row[nama_obat] (stok: $row[stok])
                  </option>";
        }
        ?>
    </select>

    <input type="number" name="jumlah" min="1" placeholder="Jumlah" required>

    <button name="simpan">Proses Transaksi</button>
</form>