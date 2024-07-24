<?php
    require 'connect.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Function to generate a random alphanumeric string
        function generateKodeBarang($length = 6) {
            $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }

        // Ambil nilai dari formulir
        $kode_barang = generateKodeBarang();
        $nama_barang = $_POST['nama_barang'];
        $nik = $_POST['nik'];
        $jumlah = $_POST['jumlah'];
        $satuan = $_POST['satuan'];

        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["foto_barang"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["foto_barang"]["name"]);
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
            } else {
            echo "File is not an image.";
            $uploadOk = 0;
            }
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }
        
        // Check file size
        if ($_FILES["foto_barang"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
        
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["foto_barang"]["tmp_name"], $target_file)) {
            // echo "The file ". htmlspecialchars( basename( $_FILES["foto_barang"]["name"])). " has been uploaded.";
            } else {
            echo "Sorry, there was an error uploading your file.";
            }
        }
        // Query untuk memeriksa apakah nama_barang sudah ada
        $checkQuery = "SELECT * FROM data_barang WHERE nama_barang = ?";
        
        // Menggunakan prepared statement
        if ($stmt = mysqli_prepare($conn, $checkQuery)) {
            mysqli_stmt_bind_param($stmt, 's', $nama_barang);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0) {
                // Nama barang sudah ada
                echo "<script>alert('Gagal menambahkan data barang. Barang dengan nama \"$nama_barang\" sudah ada.');</script>";
                echo "<script>window.location.href = 'create.php';</script>";
            } else {
                // Nama barang belum ada dan panjang nik = 16, lanjutkan proses penyimpanan
                if (strlen($nik) == 16 && ctype_digit($nik)) {
                $insertBarangQuery = "INSERT INTO data_barang (kode_barang, nama_barang, jumlah, satuan, nik, foto_barang) VALUES (?, ?, ?, ?,?,?)";
                
                if ($stmt = mysqli_prepare($conn, $insertBarangQuery)) {
                    mysqli_stmt_bind_param($stmt, 'ssisss', $kode_barang, $nama_barang, $jumlah, $satuan,$nik,$target_file);
                    if (mysqli_stmt_execute($stmt)) {
                        echo "<script>alert('Data barang berhasil ditambahkan.');</script>";
                        echo "<script>window.location.href = 'create.php';</script>";
                    } else {
                        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
                        echo "<script>window.location.href = 'create.php';</script>";
                    }
                }
            }
            echo "<script>alert('Error: NIK tidak bisa mengandung huruf!');</script>";
            echo "<script>window.location.href = 'create.php';</script>";
        }
            mysqli_stmt_close($stmt);
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Inventaris Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.js"></script>
</head>
<body>
<header class="my-10px">
    <h1 class="font-poppins mt-10 text-center text-3xl font-bold animate-flip-up animate-once animate-ease-in-out">
        「 ✦ Inventaris Barang Anugrah JW ✦ 」
    </h1>
</header>
<br><br><br>
<div class="px-6 py-3 flex space-x-2">
    <button data-modal-target="modalTambah" data-modal-toggle="modalTambah" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
        + Tambah Barang
    </button>

    <!-- tombol tambah stok -->
    <button data-modal-target="modalTambahStok" data-modal-toggle="modalTambahStok" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800" type="button">
           + Tambah Stok
    </button>
</div>
<br>
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">No.</th>
                <th scope="col" class="px-6 py-3">Kode Barang</th>
                <th scope="col" class="px-6 py-3">Nama Barang</th>
                <th scope="col" class="px-6 py-3">Jumlah</th>
                <th scope="col" class="px-6 py-3">Satuan</th>
                <th scope="col" class="px-6 py-3">Foto Barang</th>
                <th scope="col" class="px-6 py-3">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $ambildata = mysqli_query($conn, "SELECT * FROM data_barang");
        $no = 1;
        while ($data = mysqli_fetch_array($ambildata)) {
            $kode_barang = $data['kode_barang'];
            $nama_barang = $data['nama_barang'];
            $jumlah = $data['jumlah'];
            $satuan = $data['satuan'];
            $foto_barang = $data['foto_barang'];
        ?>
            <tr>
                <td scope="col" class="px-6 py-3"><?= $no++ ?></td>
                <td scope="col" class="px-6 py-3"><?= $kode_barang ?></td>
                <td scope="col" class="px-6 py-3"><?= $nama_barang ?></td>
                <td scope="col" class="px-6 py-3"><?= $jumlah ?></td>
                <td scope="col" class="px-6 py-3"><?= $satuan ?></td>
                <td scope="col" class="px-6 py-3"><?php echo "<img src='$foto_barang' width='100' height='100'" ?></td>
                <td class="flex space-x-2">
                    <!-- tombol edit -->
                    <button data-modal-target="modalEdit<?= htmlspecialchars($kode_barang) ?>" data-modal-toggle="modalEdit<?= htmlspecialchars($kode_barang) ?>" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
                        Edit
                    </button>

                    <!-- tombol hapus -->
                    <button data-modal-target="modalHapus<?= htmlspecialchars($kode_barang) ?>" data-modal-toggle="modalHapus<?= htmlspecialchars($kode_barang) ?>" class="block text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800" type="button">
                        Hapus
                    </button>
                </td>
            </tr> 
            <!-- Modal edit -->
            <div id="modalEdit<?= htmlspecialchars($kode_barang) ?>" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div class="relative p-4 w-full max-w-2xl max-h-full">
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                Edit Data Barang
                            </h3>
                            <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="modalEdit<?= htmlspecialchars($kode_barang) ?>">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <form action="update.php" method="POST">
                            <input type="hidden" name="kode_barang" value="<?= htmlspecialchars($kode_barang) ?>">
                            <div class="p-6 space-y-6 bg-white rounded-lg shadow-lg dark:bg-gray-800">
                                <div class="space-y-4">
                                    <div>
                                        <label for="nama_barang" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Barang:</label>
                                        <input type="text" value="<?= htmlspecialchars($nama_barang) ?>" name="nama_barang" id="nama_barang" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:focus:border-indigo-500" placeholder="Masukan Nama Barang" required>
                                    </div>
                                    
                                    <div>
                                        <label for="jumlah" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah:</label>
                                        <input type="number" value="<?= htmlspecialchars($jumlah) ?>" name="jumlah" id="jumlah" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:focus:border-indigo-500" placeholder="Masukan Jumlah" required>
                                    </div>
                                    
                                    <div>
                                        <label for="satuan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Satuan:</label>
                                        <input type="text" value="<?= htmlspecialchars($satuan) ?>" name="satuan" id="satuan" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:focus:border-indigo-500" placeholder="Masukan Satuan" required>
                                    </div>
                                </div>
                                
                                <!-- Modal footer -->
                                <div class="flex justify-end space-x-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-400">Simpan</button>
                                    <button type="button" data-modal-hide="modalEdit<?= htmlspecialchars($kode_barang) ?>" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-lg shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500 dark:focus:ring-gray-700">Batal</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            <!-- Modal hapus -->
            <div id="modalHapus<?= htmlspecialchars($kode_barang) ?>" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div class="relative p-4 w-full max-w-2xl max-h-full">
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                Hapus Data Barang
                            </h3>
                            <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="modalHapus<?= htmlspecialchars($kode_barang) ?>">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-4 md:p-5 space-y-4">
                            <p>Apakah Anda yakin ingin menghapus data barang ini?</p>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                            <a href="delete.php?kode_barang=<?= htmlspecialchars($kode_barang) ?>" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">Hapus</a>
                            <button data-modal-hide="modalHapus<?= htmlspecialchars($kode_barang) ?>" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-red-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Batal</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
        ?>
        </tbody>
    </table>
</div>
</body>

<!-- Modal tambah -->
<div id="modalTambah" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Tambah Data Barang
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="modalTambah">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5 space-y-4">
                <form action="create.php" method="POST" enctype="multipart/form-data">
                <div class="space-y-4">
                    <label for="nama_barang" class="block text-sm font-medium text-gray-900 dark:text-white">Nama Barang:</label>
                    <input type="text" maxlength="11" id="nama_barang" name="nama_barang" class="form-control" placeholder="Masukkan Nama Barang" required>
                    
                    <label for="nik" class="block text-sm font-medium text-gray-900 dark:text-white">NIK :</label>
                    <input type="text" minlength="16" maxlength="16" id="nik" name="nik" class="form-control" placeholder="Masukkan NIK " required>
                    
                    <label for="jumlah" class="block text-sm font-medium text-gray-900 dark:text-white">Jumlah:</label>
                    <input type="number" id="jumlah" name="jumlah" class="form-control" placeholder="Masukkan Jumlah" required>
                    
                    <label for="satuan" class="block text-sm font-medium text-gray-900 dark:text-white">Satuan:</label>
                    <input type="text" id="satuan" name="satuan" class="form-control" placeholder="Masukkan Satuan" required>

                    <label for="foto_barang" class="block text-sm font-medium text-gray-900 dark:text-white">Foto Barang:</label>
                    <input type="file" name="foto_barang" id="foto_barang" class="form-control" required>

                </div>
                    <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Tambah</button>
                        <button type="button" data-modal-hide="modalTambah" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal tambah stok -->
<div id="modalTambahStok" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Tambah Stok Barang
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="modalTambahStok">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5 space-y-4">
                <form id="myForm" action="update_stock.php" method="POST" enctype="multipart/form-data">
                    <div class="space-y-4">
                        <label for="kode_barang" class="block text-sm font-medium text-gray-900 dark:text-white">Kode Barang:</label>
                        <input type="text" id="kode_barang" name="kode_barang" class="form-control" placeholder="Masukkan Kode Barang" required>
                        
                        <label for="nama_barang" class="block text-sm font-medium text-gray-900 dark:text-white">Nama Barang:</label>
                        <input type="text" id="nama_barang" name="nama_barang" class="form-control" placeholder="Masukkan Nama Barang" required>
                        
                        <label for="tambah_stok" class="block text-sm font-medium text-gray-900 dark:text-white">Tambah Stok:</label>
                        <input type="number" id="tambah_stok" name="tambah_stok" class="form-control" placeholder="Masukkan Jumlah Stok yang Ditambahkan" required>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                        <button type="submit" disabled id="submitButton" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" disabled>Tambah</button>
                        <button type="button" data-modal-hide="modalTambahStok" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div><br><br>
<hr>
<footer>
        <p class="text-center mx-5 my-5">&copy; <span id="year"></span> Anugrah JW. All rights reserved.</p>
    </footer>

    <script>
        // Mengambil elemen dengan id "year"
        const yearElement = document.getElementById('year');
        
        // Mengambil tahun saat ini
        const currentYear = new Date().getFullYear();
        
        // Menetapkan tahun saat ini ke elemen
        yearElement.textContent = currentYear;


        document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('myForm');
        const submitButton = document.getElementById('submitButton');
        
        form.addEventListener('input', () => {
            // Check if all required fields are filled
            const isValid = form.checkValidity();
            submitButton.disabled = !isValid;
        });
    });

    </script>
    
</html>

