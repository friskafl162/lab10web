<?php 
// header.php

// Variabel $page_title akan diambil dari file yang memanggil header ini (misalnya index.php)
// Jika $page_title belum didefinisikan, ia akan menggunakan nilai default.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title ?? 'Contoh Modularisasi & CRUD'; ?></title>
    
    <link href="style.css?v=1.1" rel="stylesheet" media="screen" />
</head>
<body>
    <div class="container">
        <header>
            <h1>Sistem Data Barang</h1>
        </header>
        <nav>
            <a href="index.php">Home</a>
            <a href="about.php">Tentang</a>
        </nav>