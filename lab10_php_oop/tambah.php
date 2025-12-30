<?php
error_reporting(E_ALL);
require_once 'koneksi.php'; // Pastikan koneksi.php mendefinisikan $conn
require_once 'Barang.php'; // Panggil Class Barang

if (!$conn) {
    // Pastikan $conn ada dan koneksi berhasil
    die("Koneksi gagal: " . mysqli_connect_error());
}

// 1. Inisialisasi Objek Barang
$barang = new Barang($conn); // Buat objek Barang dengan koneksi database

if (isset($_POST['submit'])) {
    // Ambil data POST
    $data_input = [
        'nama'       => $_POST['nama'],
        'kategori'   => $_POST['kategori'],
        'harga_jual' => $_POST['harga_jual'],
        'harga_beli' => $_POST['harga_beli'],
        'stok'       => $_POST['stok'],
    ];

    $file_gambar = $_FILES['file_gambar'];
    $gambar = null;
    $upload_sukses = true;
    $pesan_error = '';

    // Logika Upload Gambar
    if ($file_gambar['error'] == 0) {
        $file_type = pathinfo($file_gambar['name'], PATHINFO_EXTENSION);
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array(strtolower($file_type), $allowed_types)) {
            $filename = uniqid() . '.' . $file_type;
            $destination = dirname(__FILE__) . '/gambar/' . $filename;

            if (move_uploaded_file($file_gambar['tmp_name'], $destination)) {
                $gambar = $filename;
            } else {
                $upload_sukses = false;
                $pesan_error = "Gagal mengupload gambar.";
            }
        } else {
            $upload_sukses = false;
            $pesan_error = "Jenis file gambar tidak valid.";
        }
    }

    // Hanya panggil method tambah jika upload gambar sukses (atau tidak ada gambar yang diupload)
    if ($upload_sukses) {
        // 2. Panggil Method dari Class Barang
        $result = $barang->tambah($data_input, $gambar);

        if ($result) {
            header('Location: index.php');
            exit;
        } else {
            // Tampilkan error dari database (diambil dari log error di class Barang)
            $pesan_error = "Gagal menyimpan data ke database.";
        }
    }
    
    // Jika ada error, tampilkan
    if (!empty($pesan_error)) {
         echo "Error: " . $pesan_error;
    }
}

require_once 'header.php'; 
?>

<h1>Tambah Barang</h1>

<div class="main">
    <form method="post" action="tambah.php" enctype="multipart/form-data">

        <div class="input">
            <label>Nama Barang</label>
            <input type="text" name="nama" required />
        </div>

        <div class="input">
            <label>Kategori</label>
            <select name="kategori" required>
                <option value="Komputer">Komputer</option>
                <option value="Elektronik">Elektronik</option>
                <option value="Hand Phone">Hand Phone</option>
            </select>
        </div>

        <div class="input">
            <label>Harga Jual</label>
            <input type="number" name="harga_jual" required />
        </div>

        <div class="input">
            <label>Harga Beli</label>
            <input type="number" name="harga_beli" required />
        </div>

        <div class="input">
            <label>Stok</label>
            <input type="number" name="stok" required />
        </div>

        <div class="input">
            <label>File Gambar</label>
            <input type="file" name="file_gambar" accept="image/*" /> 
        </div>

        <div class="submit">
            <input type="submit" name="submit" value="Simpan" />
        </div>

    </form>

</div>

<?php
require_once 'footer.php';
?>