<?php
$host = "localhost:3307";
$user = "root";
$pass = "";
$db = "bookkeeping";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) { //cek koneksi
    die("Tidak bisa terkoneksi ke database");
}

if(isset($_POST['register'])){
    $username = strtolower(stripslashes($_POST['username']));
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $password2 = mysqli_real_escape_string($koneksi, $_POST['password2']);

    //cek username
    $qsUser = mysqli_query($koneksi, "select username from user where username = '$username'");
    if(mysqli_fetch_array($qsUser)){
        echo "<script>
            alert('username sudah terdaftar');
            history.back();
        </script>"; 
        return false;
    }

    //cek konfirmasi password
    if($password !== $password2){
        echo "<script>
            alert('konfirmasi password tidak sesuai');
            
        history.back();
        </script>";
        return false;
    }

    //enkripsi password
    $password = password_hash($password, PASSWORD_DEFAULT);

    //tambahkan userbaru ke db
    $qcUser = mysqli_query($koneksi, "insert into user(username, password)  values ('$username', '$password')");

    if($username && $password){
        echo "<script>
            alert('user baru berhasil di tambahkan');
        </script>";
        header("Location: login.php");
        exit;
    }else{
        echo mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Document</title>
    <script>
        tailwind.config = {
            theme: {
                container: {
                    center: true,
                    padding: '30px',
                },
                extend: {
                    screens: {
                        '2xl': '1320px',
                    },
                }
            }
        }
    </script>
</head>

<body class="bg-teal-950">
    <div class="container mx-auto max-w-lg mt-40 border border-teal-300 rounded-lg">
        <form action="" method="POST" class="bg-teal-950 py-10">
            <h1 class="text-4xl text-white font-bold w-full text-center mb-5">Registration</h1>
            <div class="mb-3">
                <label for="username" class="ml-10 w-1/6 col-form-label font-bold text-white">Username</label>
                <div class="w-5/6 mx-auto mt-2">
                    <input type="text" class="rounded-lg border py-2 px-4 w-full" name="username" id="username">
                </div>
            </div>
            <div class="mb-3">
                <label for="password" class="ml-10 w-1/6 col-form-label font-bold text-white">Password</label>
                <div class="w-5/6 mx-auto mt-2">
                    <input type="text" class="rounded-lg border py-2 px-4 w-full" name="password" id="password">
                </div>
            </div>
            <div class="mb-3">
                <label for="password2" class="ml-10 w-1/6 col-form-label font-bold text-white">Confirm Password</label>
                <div class="w-5/6 mx-auto mt-2">
                    <input type="text" class="rounded-lg border py-2 px-4 w-full" name="password2" id="password2">
                </div>
            </div>
            <div class="flex justify-center mt-6">
                <input type="submit" name="register" value="Register" class="= w-5/6 py-2 border border-teal-300 text-white font-bold text-center rounded-lg hover:bg-teal-500 transition duration-300 mb-5">
            </div>
        </form>
    </div>
</body>

</html>