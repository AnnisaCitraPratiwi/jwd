<?php
    require 'connect.php';

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        // Ambil kode_barang dari query string
        $kode_barang = isset($_GET['kode_barang']) ? $_GET['kode_barang'] : '';

        // Pastikan kode_barang tidak kosong
        if (!empty($kode_barang)) {
            // Query untuk menghapus data dari tabel data_barang
            $deleteBarangQuery = "DELETE FROM data_barang WHERE kode_barang = ?";

            // Menggunakan prepared statement
            if ($stmt = mysqli_prepare($conn, $deleteBarangQuery)) {
                mysqli_stmt_bind_param($stmt, 's', $kode_barang);
                if (mysqli_stmt_execute($stmt)) {
                    echo "<script>alert('Data barang berhasil dihapus.');</script>";
                } else {
                    echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
                }
                mysqli_stmt_close($stmt);
            }
        } else {
            echo "<script>alert('Kode barang tidak ditemukan.');</script>";
        }

        // Redirect kembali ke halaman data_barang.php
        echo "<script>window.location.href = 'create.php';</script>";
    } else {
        echo "<script>alert('Invalid request method.');</script>";
        echo "<script>window.location.href = 'create.php';</script>";
    }
?>
