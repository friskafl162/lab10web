
<?php
error_reporting(E_ALL);
require_once 'koneksi.php';

if (isset($_POST['submit'])) {

    $id         = mysqli_real_escape_string($conn, $_POST['id']);
    $nama       = mysqli_real_escape_string($conn, $_POST['nama']);
    $kategori   = mysqli_real_escape_string($conn, $_POST['kategori']);
    $harga_jual = mysqli_real_escape_string($conn, $_POST['harga_jual']);
    $harga_beli = mysqli_real_escape_string($conn, $_POST['harga_beli']);
    $stok       = mysqli_real_escape_string($conn, $_POST['stok']);
    $gambar_lama = mysqli_real_escape_string($conn, $_POST['gambar_lama']); // Ambil nama gambar lama

    $file_gambar = $_FILES['file_gambar'];
    $gambar = $gambar_lama;

    if ($file_gambar['error'] == 0) {
        
        $file_type = pathinfo($file_gambar['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $file_type;
        $destination = dirname(__FILE__) . '/gambar/' . $filename;

        if (move_uploaded_file($file_gambar['tmp_name'], $destination)) {
            $gambar = $filename;
            
            if (!empty($gambar_lama) && file_exists('gambar/' . $gambar_lama)) {
                 unlink('gambar/' . $gambar_lama);
            }
        }
    }

    $sql  = "UPDATE data_barang SET ";
    $sql .= "nama = '{$nama}', kategori = '{$kategori}', ";
    $sql .= "harga_jual = '{$harga_jual}', harga_beli = '{$harga_beli}', stok = '{$stok}', ";
    $sql .= "gambar = '{$gambar}' "; 
    $sql .= "WHERE id_barang = '{$id}'";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        header('Location: index.php');
        exit;
    } else {
        die("Error update data: " . mysqli_error($conn));
    }
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

$sql = "SELECT * FROM data_barang WHERE id_barang = '{$id}'";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    die('Error: Data barang tidak ditemukan');
}

$data = mysqli_fetch_array($result);

function is_select($current_value, $selected_value) {
    return ($current_value == $selected_value) ? 'selected' : '';
}

$page_title = "Ubah Barang: " . $data['nama'];

require_once 'header.php';
?>

<h1>Ubah Barang</h1>
<div class="main">

<form method="post" action="ubah.php" enctype="multipart/form-data">

    <div class="input">
        <label>Nama Barang</label>
        <input type="text" name="nama" value="<?= $data['nama']; ?>" required />
    </div>

    <div class="input">
        <label>Kategori</label>
        <select name="kategori" required>
            <option value="Komputer" <?= is_select("Komputer", $data['kategori']); ?>>Komputer</option>
            <option value="Elektronik" <?= is_select("Elektronik", $data['kategori']); ?>>Elektronik</option>
            <option value="Hand Phone" <?= is_select("Hand Phone", $data['kategori']); ?>>Hand Phone</option>
        </select>
    </div>

    <div class="input">
        <label>Harga Jual</label>
        <input type="number" name="harga_jual" value="<?= $data['harga_jual']; ?>" required />
    </div>

    <div class="input">
        <label>Harga Beli</label>
        <input type="number" name="harga_beli" value="<?= $data['harga_beli']; ?>" required />
    </div>

    <div class="input">
        <label>Stok</label>
        <input type="number" name="stok" value="<?= $data['stok']; ?>" required />
    </div>

    <div class="input">
        <label>File Gambar (opsional)</label>
        <input type="file" name="file_gambar" accept="image/*" />
        <br>
        <?php if (!empty($data['gambar'])): ?>
            <small>Gambar sekarang: <b><?= $data['gambar']; ?></b> 
                ; ?>]</small>
        <?php else: ?>
            <small>Gambar sekarang: <b>Tidak Ada</b></small>
        <?php endif; ?>
    </div>

    <div class="submit">
        <input type="hidden" name="id" value="<?= $data['id_barang']; ?>" />
        <input type="hidden" name="gambar_lama" value="<?= $data['gambar']; ?>" />
        
        <input type="submit" name="submit" value="Simpan Perubahan" />
    </div>

</form>

</div>

<?php
require_once 'footer.php';
?>
