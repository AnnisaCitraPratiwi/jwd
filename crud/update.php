<?php
    require 'connect.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Ambil nilai dari formulir
        $kode_barang = $_POST['kode_barang'];
        $nama_barang = $_POST['nama_barang'];
        $jumlah = $_POST['jumlah'];
        $satuan = $_POST['satuan'];
    
        // Query untuk mengupdate data di tabel data_barang
        $updateBarangQuery = "UPDATE data_barang SET nama_barang = ?, jumlah = ?, satuan = ? WHERE kode_barang = ?";

        // Menggunakan prepared statement
        if ($stmt = mysqli_prepare($conn, $updateBarangQuery)) {
            mysqli_stmt_bind_param($stmt, 'siss', $nama_barang, $jumlah, $satuan, $kode_barang);
            if (mysqli_stmt_execute($stmt)) {
                echo "<script>alert('Data barang berhasil diupdate.');</script>";
                echo "<script>window.location.href = 'create.php';</script>";
            } else {
                echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
                echo "<script>window.location.href = 'create.php';</script>";
            }
            mysqli_stmt_close($stmt);
        }
    }
?>
