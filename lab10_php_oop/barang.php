<?php
// File: Barang.php

class Barang {
    private $conn;

    // Constructor: menerima koneksi database saat objek dibuat
    public function __construct($db_conn) {
        $this->conn = $db_conn;
    }

    /**
     * Menambahkan data barang baru ke database.
     * @param array $data Array asosiatif berisi data barang (nama, kategori, dll).
     * @param string|null $gambar Nama file gambar, atau null jika tidak ada.
     * @return bool True jika berhasil, False jika gagal.
     */
    public function tambah($data, $gambar = null) {
        // Ambil dan bersihkan data
        $nama       = mysqli_real_escape_string($this->conn, $data['nama']);
        $kategori   = mysqli_real_escape_string($this->conn, $data['kategori']);
        $harga_jual = mysqli_real_escape_string($this->conn, $data['harga_jual']);
        $harga_beli = mysqli_real_escape_string($this->conn, $data['harga_beli']);
        $stok       = mysqli_real_escape_string($this->conn, $data['stok']);
        $gambar_db  = mysqli_real_escape_string($this->conn, $gambar); // Bersihkan nama file gambar

        // Query INSERT
        $sql = "INSERT INTO data_barang (nama, kategori, harga_jual, harga_beli, stok, gambar) 
                VALUES ('{$nama}', '{$kategori}', '{$harga_jual}', '{$harga_beli}', '{$stok}', '{$gambar_db}')";

        $result = mysqli_query($this->conn, $sql);

        if (!$result) {
            // Opsional: Anda bisa menggunakan exception atau logging di sini
            error_log("SQL Error: " . mysqli_error($this->conn) . " Query: " . $sql);
        }

        return $result;
    }
    
    // Anda bisa menambahkan method lain seperti getAll(), getById(), update(), delete() di sini
}