<?php
session_start();
if(isset($_SESSION['login'])){
    header("Location: index.php");
    exit;
}

$host = "localhost:3307";
$user = "root";
$pass = "";
$db = "bookkeeping";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) { //cek koneksi
    die("Tidak bisa terkoneksi ke database");
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $sql1 = "select * from user where username = '$username'";
    $q1 = mysqli_query($koneksi, $sql1);

    //cek username
    if (mysqli_num_rows($q1) === 1) {
        //cek password
        $row = mysqli_fetch_assoc($q1); //mengambil data 1 row
        if (password_verify($password, $row['password'])) { //cek antara hash dan string
            //set session
            $_SESSION['uname'] = $_POST['username'];
            $_SESSION['login'] =  true;

            header('Location: index.php');
            exit;
        }
    }
    $error = "Login failed";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Login</title>
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
            <h1 class="text-4xl text-white font-bold w-full text-center mb-5">Login</h1>
            <?php
            if (isset($error)) {
            ?>
            <div class="w-5/6 mx-auto bg-red-100 py-2 px-3 mb-2">
                <?php echo $error ?>
            </div>
            <?php
            }
            ?>
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
            <div class="flex justify-center mt-6">
                <input type="submit" name="login" value="Login" class="= w-5/6 py-2 border border-teal-300 text-white font-bold text-center rounded-lg hover:bg-teal-500 transition duration-300 mb-5">
            </div>
            <div class="w-5/6 bg-white h-0.5 mx-auto"></div>
            <div class="mt-4 w-5/6 mx-auto text-center text-white text-sm">
                <p>Don't have an account? <a href="regist.php" class="text-teal-300 hover:text-teal-500 transition">Register</a></p>
            </div>
        </form>
    </div>
</body>
</html>