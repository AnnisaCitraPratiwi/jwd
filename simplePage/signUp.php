<?php 

require 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $insertQuery = "INSERT INTO data_akun(`name`, `email`, `password`) VALUES (?, ?, ?)";

    // Menggunakan prepared statement
    if ($stmt = mysqli_prepare($conn, $insertQuery)) {
        mysqli_stmt_bind_param($stmt, 'sss', $name, $email, $hashedPassword);
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Akun berhasil didaftarkan.');</script>";
            echo "<script>window.location.href = 'signUp.php';</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
            echo "<script>window.location.href = 'signUp.php';</script>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
        echo "<script>window.location.href = 'signUp.php';</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Page</title>
    <link href="./output.css" rel="stylesheet">
</head>
<body>
    <section>
        <div class="grid grid-cols-2">
            <div>
                <div class="h-screen flex justify-center">  
                <form method="POST" action="" class="max-w-sm mx-auto place-self-center self-center">
                    <h1 class="mb-1 text-center font-semibold text-2xl">Create your account</h1>
                    <h5 class="mt-3 mb-10 text-gray-500 text-center font-small text-8px">Let's get started with your 30 days free trial</h5>
                    <div class="mb-5">
                    <label for="name" class="block mb-2 text-sm font-medium text-white-900 dark:text-gray px-1">Name<span class="text-red-500">*</span></label>
                    <input type="text" id="name" class="rounded-full shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-white-300 dark:border-white-600 rounded-full dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" placeholder="Enter your name" required />
                    </div>
                    
                    <div class="mb-5">
                    <label for="email" class="block mb-2 text-sm font-medium text-white-900 dark:text-gray px-1">Email<span class="text-red-500">*</span></label>
                    <input type="email" id="email" class="rounded-full shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-white-300 dark:border-white-600 rounded-full dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" placeholder="Enter your email" required />
                    </div>
                    
                    <div class="mb-5">
                    <label for="password" class="block mb-2 text-sm font-medium text-white-900 dark:text-gray px-1">Password<span class="text-red-500">*</span></label>
                    <input type="password" id="password" class="rounded-full shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm  focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-white-300 dark:border-white-600 rounded-full dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:shadow-sm-light" placeholder="Enter your password" required />
                    </div>

                    <div class="flex items-start mb-5">
                    <div class="flex items-center h-5">
                        <input id="terms" type="checkbox" value="" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-black-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-black-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800" required />
                    </div>
                    <label for="terms" class="ms-2 text-sm font-medium text-gray-900 dark:text-black-300">I agree all Term, Privacy Policy and Fees</label>
                    </div>
                    <button type="submit" class="rounded-full w-full text-white bg-black hover:bg-black-800 focus:ring-black-4 focus:outline-none focus:ring-black-300 font-medium text-sm px-5 py-2.5 my-3 text-center dark:bg-black-600 dark:hover:bg-black-700 dark:focus:ring-black-800">Sign Up</button>
                    <label class="text-sm font-medium text-gray-900 dark:text-black-300">Already have an account? <a href="login.html" class="text-green-700-500 hover:underline dark:text-green-500">Log in</a></label>
                </form>
  
  
                </div>
            </div>

            <div>
                <div class="h-screen flex justify-center">
                    <img src="https://images.unsplash.com/photo-1567016557389-5246a1940bdc?q=80&w=1780&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"alt="eror">
                </div>
            </div>
        </div>
    </section>
</body>
</html>