# UAS Pemrograman Web

NAMA: SYAFIRA LUTHFI AZZAHRA

NIM: 312410353

KELAS: TI.24.A.4

MATA KULIAH: PEMROGRAMAN WEB

# SISTEM APOTEK

Sistem Apotek berbasis web adalah aplikasi yang digunakan untuk mengelola data obat secara digital, meliputi proses penambahan, pengubahan, penghapusan, dan penampilan data obat, sehingga membantu apoteker dalam mengelola informasi obat dengan lebih cepat dan terstruktur. Sistem ini dilengkapi dengan fitur login yang membedakan hak akses antara admin dan user, serta fitur pencarian dan pagination untuk memudahkan pencarian data obat. Dengan tampilan yang responsive, sistem apotek dapat diakses melalui berbagai perangkat dan mendukung proses pengelolaan data yang lebih efisien dibandingkan dengan sistem manual.

# STRUKTUR

<img width="370" height="335" alt="image" src="https://github.com/user-attachments/assets/f9df3d68-d9b9-487b-aca9-d201c171942c" />

## CREATE DATABASE

```php
CREATE DATABASE apotek;
USE apotek;
```

## Tabel Apoteker

```php
CREATE TABLE apoteker (
    id_apoteker INT AUTO_INCREMENT PRIMARY KEY,
    nama_apoteker VARCHAR(100) NOT NULL,
    jam_kerja VARCHAR(50)
) ENGINE=InnoDB;
```

## Tabel Obat

```php
CREATE TABLE obat (
    id_obat INT AUTO_INCREMENT PRIMARY KEY,
    nama_obat VARCHAR(100) NOT NULL,
    harga DECIMAL(10,2) NOT NULL,
    stok INT NOT NULL
) ENGINE=InnoDB;
```

## Tabel Transaksi

```php
CREATE TABLE transaksi (
    id_transaksi INT AUTO_INCREMENT PRIMARY KEY,
    id_apoteker INT NOT NULL,
    tanggal DATETIME DEFAULT CURRENT_TIMESTAMP,
    total_harga DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_apoteker) REFERENCES apoteker(id_apoteker)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;
```

## Tabel Detail_transaksi

```php
CREATE TABLE detail_transaksi (
    id_detail INT AUTO_INCREMENT PRIMARY KEY,
    id_transaksi INT NOT NULL,
    id_obat INT NOT NULL,
    jumlah INT NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_transaksi) REFERENCES transaksi(id_transaksi)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_obat) REFERENCES obat(id_obat)
        ON UPDATE CASCADE
) ENGINE=InnoDB;
```

## Tabel users

```php
CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB;
```

<img width="1289" height="759" alt="image" src="https://github.com/user-attachments/assets/d00a3e1e-a2a2-432c-9342-d9ba81880ed3" />

# .htaccess

```
RewriteEngine On

RewriteRule ^apoteker$ master.php?menu=apoteker [L]
RewriteRule ^obat$ master.php?menu=obat [L]
RewriteRule ^transaksi$ master.php?menu=transaksi [L]
RewriteRule ^riwayat$ master.php?menu=riwayat [L]
```

Kode `.htaccess` tersebut digunakan untuk mengaktifkan fitur URL rewrite agar alamat website menjadi lebih rapi dan mudah dibaca. Baris `RewriteEngine On` berfungsi untuk menghidupkan mesin rewrite pada Apache sehingga aturan di bawahnya dapat dijalankan. Setiap `RewriteRule` bertugas mengubah URL sederhana seperti `/apoteker`, `/obat`, `/transaksi`, dan `/riwayat` menjadi pemanggilan file `master.php` dengan parameter `menu` yang sesuai, misalnya `/apoteker` akan diproses sebagai `master.php?menu=apoteker`. Dengan cara ini, meskipun di browser hanya terlihat URL singkat, sistem PHP tetap menerima nilai `$_GET['menu']` untuk menentukan halaman yang ditampilkan. Tanda `[L]` di setiap aturan berarti bahwa jika aturan tersebut cocok, maka Apache akan berhenti memproses aturan lain.

# Koneksi.php

```php
<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "apotek";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
```

Kode PHP tersebut berfungsi untuk menghubungkan aplikasi dengan database MySQL. Variabel `$host`, `$user`, `$pass`, dan `$db` digunakan untuk menyimpan informasi koneksi, yaitu alamat server database, username, password, dan nama database yang akan diakses. Fungsi `mysqli_connect()` kemudian dipakai untuk membuat koneksi ke database berdasarkan data tersebut dan hasilnya disimpan ke dalam variabel `$conn`. Setelah itu, dilakukan pengecekan kondisi koneksi menggunakan `if (!$conn)`, yang berarti jika koneksi gagal maka program akan dihentikan dengan perintah `die()` dan menampilkan pesan kesalahan dari `mysqli_connect_error()`. Jika koneksi berhasil, maka variabel `$conn` dapat digunakan oleh file PHP lain untuk menjalankan query ke database apotek.

# LOGIN SEBAGAI ADMIN/APOTEKER

## Login.php

```php
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
```

Kode PHP tersebut merupakan halaman login pada sistem web apotek yang berfungsi untuk memverifikasi username dan password pengguna sebelum mengakses halaman utama. Fungsi `session_start()` digunakan untuk memulai sesi, sedangkan `include "koneksi.php"` berfungsi menghubungkan sistem dengan database. Ketika tombol login ditekan, data `username` dan `password` diambil dari form dan diamankan menggunakan `mysqli_real_escape_string()` untuk mencegah input berbahaya. Selanjutnya, sistem melakukan query ke tabel `users` untuk mencocokkan data login. Jika data ditemukan, sistem menyimpan informasi pengguna ke dalam session seperti `$_SESSION['admin']`, `$_SESSION['nama']`, dan `$_SESSION['level']`, lalu mengarahkan pengguna ke halaman `master.php`. Jika data tidak cocok, sistem menampilkan pesan kesalahan bahwa username atau password salah. Tampilan login dibuat sederhana menggunakan HTML dan CSS agar mudah digunakan oleh pengguna.

* `session_start()` digunakan untuk memulai sesi login
* `include "koneksi.php"` berfungsi menghubungkan halaman login dengan database
* `$_POST['login']` digunakan untuk mengecek apakah tombol login ditekan
* `mysqli_real_escape_string()` digunakan untuk mengamankan input username dan password
* Query `SELECT * FROM users` digunakan untuk memverifikasi data login
* `mysqli_num_rows()` digunakan untuk mengecek apakah data user ditemukan
* `$_SESSION['admin']`, `$_SESSION['nama']`, dan `$_SESSION['level']` digunakan untuk menyimpan data pengguna yang login
* `header("Location: master.php")` digunakan untuk mengarahkan pengguna ke halaman utama setelah login berhasil

<img width="955" height="1019" alt="Cuplikan layar 2026-01-11 113139" src="https://github.com/user-attachments/assets/c972f122-c736-4696-9d8d-3f74bb64a268" />

## Master.php

```php
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
```

Kode PHP tersebut merupakan halaman utama sistem web apotek yang menggunakan fungsi `session_start()` untuk memulai sesi pengguna dan `include "koneksi.php"` untuk menghubungkan sistem dengan database. Pada bagian awal, dilakukan pengecekan sesi menggunakan `if (!isset($_SESSION['admin']))` untuk memastikan bahwa pengguna telah login, jika belum maka diarahkan ke halaman `login.php`. Variabel `$menu` diambil dari `$_GET['menu']` untuk menentukan halaman yang akan ditampilkan, sedangkan `$level` diambil dari `$_SESSION['level']` untuk menentukan hak akses pengguna. Struktur percabangan `if–elseif` digunakan untuk memanggil file seperti `apoteker.php`, `obat.php`, `transaksi.php`, dan `transaksi_detail.php` sesuai menu yang dipilih dan level pengguna.

* `session_start()` digunakan untuk memulai dan mengelola sesi login
* `include "koneksi.php"` berfungsi menghubungkan sistem dengan database
* `$_SESSION['admin']` digunakan untuk mengecek status login pengguna
* `$_SESSION['level']` digunakan untuk menentukan hak akses pengguna
* `$_GET['menu']` digunakan untuk menentukan halaman yang ditampilkan
* Percabangan `if–elseif` digunakan untuk membatasi akses halaman berdasarkan level

<img width="956" height="1017" alt="image" src="https://github.com/user-attachments/assets/5a015cec-d749-4f20-9f1b-ffe470ad290d" />

## Apotekerr.php

```php
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
```

Kode PHP tersebut digunakan untuk mengelola data apoteker pada sistem web apotek, yang meliputi proses menambah dan menghapus data. Pada bagian awal, sistem mengecek apakah tombol simpan ditekan menggunakan `isset($_POST['simpan'])`. Jika iya, data `nama` dan `jam_kerja` diambil dari form lalu disimpan ke dalam tabel `apoteker` menggunakan perintah `INSERT INTO`. Setelah data berhasil disimpan, sistem melakukan redirect ke halaman apoteker menggunakan JavaScript agar tampilan diperbarui. Selain itu, kode juga menangani proses penghapusan data dengan mengecek parameter `$_GET['hapus']`, kemudian menjalankan query `DELETE FROM apoteker` berdasarkan `id_apoteker` yang dipilih. Data apoteker yang tersimpan ditampilkan dalam bentuk tabel lengkap dengan kolom aksi hapus, sehingga admin dapat mengelola data apoteker secara langsung melalui sistem.

* `isset($_POST['simpan'])` digunakan untuk mengecek proses penambahan data apoteker
* `$_POST['nama']` dan `$_POST['jam_kerja']` digunakan untuk mengambil input dari form
* Query `INSERT INTO apoteker` digunakan untuk menyimpan data ke database
* `isset($_GET['hapus'])` digunakan untuk mengecek aksi hapus data
* Query `DELETE FROM apoteker` digunakan untuk menghapus data berdasarkan `id_apoteker`
* `mysqli_query()` digunakan untuk menjalankan perintah SQL
* Tabel HTML digunakan untuk menampilkan data apoteker beserta jam kerja dan aksi
* Konfirmasi `onclick="return confirm(...)"` digunakan untuk mencegah penghapusan tidak sengaja

<img width="958" height="1017" alt="image" src="https://github.com/user-attachments/assets/e763bc6f-378a-44cf-b635-6cc896034518" />

## Obat.php

```php
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
```

Kode PHP tersebut digunakan untuk mengelola data obat pada sistem apotek dengan menerapkan pembatasan akses berdasarkan level pengguna. Di awal kode, variabel `$level` diambil dari `$_SESSION['level']` untuk menentukan hak akses user. Proses penambahan data obat hanya dapat dilakukan oleh admin, yang dicek melalui kondisi `isset($_POST['simpan']) && $level == 'admin'`. Jika terpenuhi, data `nama`, `harga`, dan `stok` diambil dari form lalu disimpan ke tabel `obat` menggunakan query `INSERT INTO`. Setelah data berhasil disimpan, sistem melakukan redirect ke halaman obat. Pada bagian tampilan, form input dan kolom aksi hanya ditampilkan jika user adalah admin, sedangkan user non-admin hanya dapat melihat data obat dalam tabel tanpa bisa menambah atau mengedit data.

* `$level = $_SESSION['level']` digunakan untuk mengambil level akses pengguna
* `isset($_POST['simpan']) && $level == 'admin'` digunakan untuk membatasi proses simpan hanya untuk admin
* `$_POST['nama']`, `$_POST['harga']`, dan `$_POST['stok']` digunakan untuk mengambil input dari form
* Query `INSERT INTO obat` digunakan untuk menyimpan data obat ke database
* `window.location='master.php?menu=obat'` digunakan untuk refresh halaman setelah simpan data
* Kondisi `if ($level == 'admin')` digunakan untuk menampilkan form input obat
* User non-admin hanya ditampilkan pesan akses lihat data
* Query `SELECT * FROM obat ORDER BY id_obat DESC` digunakan untuk menampilkan data obat terbaru
* Link `obat_edit&id=...` digunakan sebagai akses edit data khusus admin
* Tabel HTML digunakan untuk menampilkan nama obat, harga, stok, dan aksi edit

<img width="958" height="1023" alt="image" src="https://github.com/user-attachments/assets/11ee4fc4-e380-4100-8cf4-66aa3e86982d" />

## Obat_edit.php

```php
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
```

Kode PHP tersebut digunakan untuk mengedit data obat yang sudah tersimpan di database. File ini menerima `id_obat` melalui parameter `$_GET['id']`, lalu mengambil data obat terkait menggunakan query `SELECT` agar nilainya bisa ditampilkan kembali ke dalam form. Ketika tombol Update ditekan, sistem akan mengecek `isset($_POST['update'])`, kemudian mengambil data baru dari input form dan menjalankan query `UPDATE` untuk memperbarui nama obat, harga, dan stok berdasarkan `id_obat` yang dipilih. Setelah proses update berhasil, pengguna langsung diarahkan kembali ke halaman data obat pada menu utama.

* `include "koneksi.php"` digunakan untuk menghubungkan file dengan database
* `$_GET['id']` digunakan untuk mengambil ID obat yang akan diedit
* Query `SELECT * FROM obat WHERE id_obat='$id'` digunakan untuk menampilkan data lama ke form
* `mysqli_fetch_assoc($q)` digunakan untuk mengambil data obat dalam bentuk array
* `isset($_POST['update'])` digunakan untuk mengecek apakah tombol update ditekan
* `$_POST['nama']`, `$_POST['harga']`, dan `$_POST['stok']` digunakan untuk mengambil data hasil edit
* Query `UPDATE obat SET ... WHERE id_obat='$id'` digunakan untuk memperbarui data obat
* `header("Location: master.php?menu=obat")` digunakan untuk kembali ke halaman daftar obat
* Form HTML menampilkan data lama sebagai `value` agar mudah diedit
* Tombol Batal digunakan untuk kembali tanpa menyimpan perubahan

<img width="953" height="1016" alt="image" src="https://github.com/user-attachments/assets/402feffa-0760-4e6a-964e-3909e2dc1f5f" />

## Transaksi.php

```php
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
```

Kode PHP ini digunakan untuk memproses transaksi penjualan obat pada sistem apotek. Saat tombol Proses Transaksi ditekan (`isset($_POST['simpan'])`), sistem mengambil data apoteker, obat, dan jumlah pembelian dari form. Selanjutnya sistem mengecek harga dan stok obat yang dipilih melalui query ke tabel `obat`. Jika jumlah yang diminta melebihi stok, sistem menampilkan peringatan. Jika stok mencukupi, sistem akan menghitung subtotal, menyimpan data transaksi ke tabel `transaksi`, menyimpan detail pembelian ke tabel `detail_transaksi`, mengurangi stok obat sesuai jumlah yang dibeli, lalu mengarahkan pengguna ke halaman riwayat transaksi.

* `include "koneksi.php"` digunakan untuk menghubungkan file dengan database
* `isset($_POST['simpan'])` digunakan untuk mengecek apakah transaksi diproses
* `$_POST['apoteker']`, `$_POST['obat']`, dan `$_POST['jumlah']` digunakan untuk mengambil data input transaksi
* Query `SELECT harga, stok FROM obat` digunakan untuk mengecek harga dan ketersediaan stok obat
* Percabangan `if ($jumlah > $obat['stok'])` digunakan untuk validasi stok
* `$subtotal = $obat['harga'] * $jumlah` digunakan untuk menghitung total harga pembelian
* Query `INSERT INTO transaksi` digunakan untuk menyimpan data transaksi utama
* `mysqli_insert_id($conn)` digunakan untuk mengambil ID transaksi terakhir
* Query `INSERT INTO detail_transaksi` digunakan untuk menyimpan detail obat yang dibeli
* Query `UPDATE obat SET stok = stok - $jumlah` digunakan untuk mengurangi stok obat
* `header("Location: master.php?menu=riwayat")` digunakan untuk berpindah ke halaman riwayat transaksi
* `<select name="apoteker">` digunakan untuk memilih apoteker yang melayani transaksi
* `<select name="obat">` menampilkan daftar obat beserta stoknya
* Input `jumlah` digunakan untuk menentukan jumlah obat yang dibeli

<img width="952" height="1018" alt="image" src="https://github.com/user-attachments/assets/20d1e20f-0d37-4132-a759-8730ceb93f33" />

## Detail_transaksi.php

```php
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
```

Kode PHP ini digunakan untuk menampilkan dan mengelola riwayat transaksi penjualan obat pada sistem apotek. Pada bagian awal, sistem mengecek apakah terdapat parameter `hapus` pada URL untuk menghapus data transaksi tertentu. Jika ada, sistem akan menghapus terlebih dahulu data yang berelasi pada tabel `detail_transaksi`, kemudian menghapus data utama pada tabel `transaksi` agar tidak terjadi konflik relasi data. Setelah proses penghapusan selesai, pengguna akan diarahkan kembali ke halaman riwayat transaksi. Selanjutnya, sistem menampilkan daftar riwayat transaksi dalam bentuk tabel yang berisi informasi tanggal transaksi, nama apoteker, daftar obat yang dibeli, total harga, serta aksi penghapusan data.

* `include "koneksi.php"` digunakan untuk menghubungkan file dengan database
* `isset($_GET['hapus'])` digunakan untuk mendeteksi aksi hapus transaksi
* Query `DELETE FROM detail_transaksi` dijalankan terlebih dahulu untuk menjaga konsistensi relasi data
* Query `DELETE FROM transaksi` digunakan untuk menghapus data transaksi utama
* `header("Location: master.php?menu=riwayat")` digunakan untuk memuat ulang halaman riwayat
* Query `SELECT` dengan `JOIN` digunakan untuk menggabungkan tabel transaksi, apoteker, detail_transaksi, dan obat
* `GROUP_CONCAT(o.nama_obat)` digunakan untuk menampilkan beberapa nama obat dalam satu transaksi
* `SUM(d.jumlah * o.harga)` digunakan untuk menghitung total harga transaksi
* `GROUP BY t.id_transaksi` digunakan untuk mengelompokkan data berdasarkan transaksi
* `ORDER BY t.id_transaksi DESC` digunakan untuk menampilkan transaksi terbaru terlebih dahulu
* `number_format()` digunakan untuk memformat total harga ke bentuk rupiah
* Link Hapus dilengkapi konfirmasi agar penghapusan tidak terjadi secara tidak sengaja

<img width="956" height="1017" alt="image" src="https://github.com/user-attachments/assets/58f45999-57be-4103-9d45-e6452823af76" />

## Logout.php

```php
<?php
session_start();
session_destroy();

header("Location: login.php");
exit();
?>
```

Kode PHP ini digunakan untuk proses logout pengguna pada sistem apotek. Ketika file ini diakses, fungsi `session_start()` dipanggil terlebih dahulu untuk memastikan sesi yang sedang aktif dapat dikenali oleh sistem. Selanjutnya, `session_destroy()` digunakan untuk menghapus seluruh data sesi yang tersimpan, sehingga informasi login seperti username, nama, dan level pengguna akan hilang. Setelah sesi berhasil dihancurkan, pengguna langsung diarahkan kembali ke halaman login menggunakan fungsi `header("Location: login.php")`, dan `exit()` digunakan untuk menghentikan eksekusi script agar tidak ada kode lain yang dijalankan setelah proses logout selesai.

* `session_start()` berfungsi untuk memulai atau melanjutkan sesi yang sedang aktif
* `session_destroy()` digunakan untuk menghapus seluruh data sesi pengguna
* Data login seperti hak akses dan identitas pengguna akan terhapus dari session
* `header("Location: login.php")` digunakan untuk mengarahkan pengguna ke halaman login
* `exit()` berfungsi menghentikan proses script setelah redirect
* Kode ini memastikan pengguna benar-benar keluar dari sistem dan tidak bisa mengakses halaman yang membutuhkan login tanpa masuk kembali

# LOGIN SEBAGAI PEMBELI

<img width="956" height="1019" alt="image" src="https://github.com/user-attachments/assets/0a7d112a-37dc-4dca-9c6f-fee280e42754" />
<img width="957" height="1017" alt="image" src="https://github.com/user-attachments/assets/206bfe1c-9a23-4f64-94f0-80ebf0e12571" />
