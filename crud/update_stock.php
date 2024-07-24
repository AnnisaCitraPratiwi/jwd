<?php
require 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil nilai dari formulir
    $kode_barang = $_POST['kode_barang'];
    $nama_barang = $_POST['nama_barang'];
    $tambah_stok = $_POST['tambah_stok'];

    // Query untuk memeriksa apakah barang dengan nama dan kode tersebut sudah ada di database
    $checkQuery = "SELECT * FROM data_barang WHERE kode_barang = ? AND nama_barang = ?";
    
    if ($stmt = mysqli_prepare($conn, $checkQuery)) {
        mysqli_stmt_bind_param($stmt, 'ss', $kode_barang, $nama_barang);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        
        if (mysqli_stmt_num_rows($stmt) > 0) {
            // Barang sudah ada, lakukan update
            $updateQuery = "UPDATE data_barang SET jumlah = jumlah + ? WHERE kode_barang = ? AND nama_barang = ?";
            if ($stmtUpdate = mysqli_prepare($conn, $updateQuery)) {
                mysqli_stmt_bind_param($stmtUpdate, 'iss', $tambah_stok, $kode_barang, $nama_barang);
                if (mysqli_stmt_execute($stmtUpdate)) {
                    echo "<script>alert('Stok berhasil diperbarui.');</script>";
                } else {
                    echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
                }
                mysqli_stmt_close($stmtUpdate);
            }
        } else {
            echo "<script>alert('Barang dengan kode atau nama tersebut tidak ditemukan.');</script>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
    // Arahkan kembali ke halaman sebelumnya
    echo "<script>window.location.href = 'create.php';</script>";
}
?>
