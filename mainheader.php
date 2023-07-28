<?php
session_start();
if(!isset($_SESSION['login'])){
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookeeper</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .accordion-header {
            padding: 16px 24px 16px 24px;
            cursor: pointer;
        }

        .accordion-body {
            max-height: 0;
            overflow: hidden;
            transition-property: all;
            transition-duration: 200ms;
            transition-timing-function: ease-out;
        }
    </style>
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
    <header
        class="bg-teal-950 fixed top-0 left-0 w-full flex z-10 items-center z-10 border-b-2 border-teal-200 shadow-2xl shadow-zinc-900">
        <div class="container">
            <div class="flex items-center justify-between relative">
                <div class="px-4">
                    <a href="index.php" class="font-bold text-2xl text-white block py-6 pl-6">Shopflow.</a>
                </div>
                <div class="flex items-center ml-96 pl-62">
                    <a href="index.php"
                        class="text-slate-200 text-base text-lg hover:text-teal-200 transition duration-700 pr-9">//
                        Home</a>
                    <a href="product.php"
                        class="text-slate-200 text-base text-lg hover:text-teal-200 transition duration-700 pr-9">//
                        Product</a>
                    <a href="expenditure.php"
                        class="text-slate-200 text-base text-lg hover:text-teal-200 transition duration-700 pr-9">//
                        Expenditure</a>
                    <a href="sales.php"
                        class="text-slate-200 text-base text-lg hover:text-teal-200 transition duration-700 pr-9">//
                        Sales</a>
                    <div class="pl-14">
                        <p class="text-slate-200 text-base text-lg hover:text-teal-200 transition duration-700 pr-9">Hi, <?php echo $_SESSION['uname']; ?></p>
                        <a href="logout.php" onclick="return confirm('Are you sure ?')"
                            class="text-slate-200 text-base text-lg hover:text-teal-200 transition duration-700 pr-9"> <i class='bx bx-log-out'></i>
                            Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </header>