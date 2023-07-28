<?php
$host = "localhost:3307";
$user = "root";
$pass = "";
$db = "bookkeeping";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) { //cek koneksi
    die("Tidak bisa terkoneksi ke database");
}

$nama = "";
$harga = "";
$stok = "";
$berat = "";
$gambar = "";
$error = "";
$sukses = "";
$gambar = "";
$namaGambar = "";
$namaTmpGambar = "";
$gambarError = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($op == 'edit') {
    $id = $_GET['id'];
    $qsProduct = mysqli_query($koneksi, "select * from product where id = '$id'");
    $rProduct = mysqli_fetch_array($qsProduct);
    $nama = $rProduct['nama'];
    $harga = $rProduct['harga'];
    $stok = $rProduct['stok'];
    $berat = $rProduct['berat'];
    $gambar = $rProduct['gambar'];

    if ($nama == '') {
        $error = "Data not found";
    }
}

if($op == 'delete'){
    $id = $_GET['id'];
    $qdProduct = mysqli_query($koneksi, "delete from product where id = '$id'");
    if ($qdProduct) {
        $sukses = "Data successfully deleted";
    } else {
        $error = "Data failed to delete";
    }
}

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $berat = $_POST['berat'];

    $gambar = $_FILES['gambar'];
    $namaGambar = $_FILES['gambar']['name'];
    $namaTmpGambar = $gambar['tmp_name'];
    $gambarError = $gambar['error'];

    if ($nama && $harga && $stok && $berat && $namaGambar) {
        if ($op == 'edit') {
            $targetDir = 'uploads/';
            $targetPath = $targetDir.$namaGambar;
            move_uploaded_file($namaTmpGambar, $targetPath);
            $quProduct = mysqli_query($koneksi, "update product set nama = '$nama', harga = '$harga', stok = '$stok', berat = '$berat', gambar = '$targetPath' where id = '$id'");
            if ($quProduct) {
                $sukses = "Data successfully updated";
            } else {
                $error = "Data failed to update";
            }
        } else{
            $targetDir = 'uploads/';
            $targetPath = $targetDir.$namaGambar;
            move_uploaded_file($namaTmpGambar, $targetPath);
            $qcProduct = mysqli_query($koneksi, "insert into product(nama, harga, stok, berat, gambar) values ('$nama', '$harga', '$stok', '$berat', '$targetPath')");
            if ($qcProduct) {
                $sukses = "Successfully add new data";
            } else {
                $error = "Failed to enter data";
            }
        }
    }else{
        $error = "Enter data first";
    }
}

?>

<?php include_once("mainheader.php"); ?>
<div class="mt-32">
    <div class="container">
        <h1 class="text-5xl font-bold text-slate-200">Product</h1>
    </div>
</div>
<div class="mt-8">
    <div class="container">
        <div class="bg-white shadow-md rounded-lg overflow-hidden max-w-4xl mx-auto mb-6 border border-white">
            <div class="px-4 py-2 bg-teal-900 text-white">
                Create / Edit Data
            </div>
            <div class="px-6 py-6 bg-teal-950">
                <?php
                if ($error) {
                    ?>
                    <div class="w-full bg-red-100 py-2 px-3 mb-2">
                        <?php echo $error ?>
                    </div>
                    <?php
                    header("refresh:5;url=product.php");
                }
                ?>
                <?php
                if ($sukses) {
                    ?>
                    <div class="w-full bg-green-100 py-2 px-3 mb-2">
                        <?php echo $sukses ?>
                    </div>
                    <?php
                    header("refresh:5;url=product.php");
                }
                ?>
                <!-- form -->
                <form action="" method="POST" class="bg-teal-950" enctype="multipart/form-data">
                    <div class="mb-5 flex flex-col md:flex-row items-center">
                        <label for="nama" class="w-1/6 col-form-label font-bold text-white">Product Name</label>
                        <div class="w-5/6">
                            <input type="text" class="rounded-lg border py-2 px-4 w-full" name="nama" id="nama"
                                value="<?php echo $nama ?>">
                        </div>
                    </div>
                    <div class="mb-5 flex flex-col md:flex-row items-center">
                        <label for="harga" class="w-1/6 col-form-label font-bold text-white">Price</label>
                        <div class="w-5/6">
                            <input type="text" class="rounded-lg border py-2 px-4 w-full" name="harga" id="harga"
                                value="<?php echo $harga ?>">
                        </div>
                    </div>
                    <div class="mb-5 flex flex-col md:flex-row items-center">
                        <label for="stok" class="w-1/6 col-form-label font-bold text-white">Stock</label>
                        <div class="w-5/6">
                            <input type="text" class="rounded-lg border py-2 px-4 w-full" name="stok" id="stok"
                                value="<?php echo $stok ?>">
                        </div>
                    </div>
                    <div class="mb-5 flex flex-col md:flex-row items-center">
                        <label for="berat" class="w-1/6 col-form-label font-bold text-white">Weight</label>
                        <div class="w-5/6">
                            <input type="text" class="rounded-lg border py-2 px-4 w-full" name="berat" id="berat"
                                value="<?php echo $berat ?>">
                        </div>
                    </div>
                    <div class="mb-5 flex flex-col md:flex-row items-center">
                        <label for="gambar" class="w-1/6 col-form-label font-bold text-white">Picture</label>
                        <div class="w-5/6">
                            <input type="file" class="rounded-lg border py-2 px-4 w-full text-white" name="gambar" id="gambar"
                                value="<?php echo $gambar ?>">
                        </div>
                    </div>
                    <div>
                        <input type="submit" name="simpan" value="Save Data" class="px-4 py-3 bg-blue-600 text-white font-bold text-center rounded-lg hover:bg-blue-700 transition duration-200">
                        <a href="#proRecap" class="w-1/6 px-4 py-3 ml-3 bg-cyan-600 text-white font-bold text-center rounded-lg hover:bg-cyan-700 transition duration-200">See All Product</a>
                    </div>
                </form>
            </div>
        </div>
        <div id="proRecap"></div>
        <h1 class="text-3xl font-bold text-slate-200 mt-20 text-center">All Product</h1>
        <div class="flex flex-wrap mx-4 justify-center mb-10">
            <?php
            $qsProduct = mysqli_query($koneksi, "select * from product order by id");
            $urut = 1;
            while ($rProduct = mysqli_fetch_array($qsProduct)) {
                $id = $rProduct['id'];
                $nama = $rProduct['nama'];
                $harga = $rProduct['harga'];
                $stok = $rProduct['stok'];
                $berat = $rProduct['berat'];
                $gambar = $rProduct['gambar'];
                ?>
                <div
                    class="w-1/4 border border-teal-300 rounded-lg py-4 px-6 mx-4 my-4 shadow-2xl hover:bg-teal-900 transition duration-200">
                    <img src="<?php echo $gambar ?>" alt="" class="max-h-40 mx-auto mb-5">
                    <div class="flex items-center">
                        <div class="w-2/3">
                            <p class="text-3xl font-bold text-slate-300 pt-1">
                                <?php echo $nama ?>
                            </p>
                            <p class="text-sm text-white">
                                Rp. <?php echo $harga ?>
                            </p>
                            <p class="text-sm text-white">
                                <?php echo $berat ?> gr
                            </p>
                        </div>
                        <div class="w-1/3">
                            <p class="text-base text-white text-center">Stok : </p>
                            <p class="text-5xl font-bold text-slate-300 py-1 text-center">
                                <?php echo $stok ?>
                            </p>
                        </div>
                    </div>
                    <div class="flex justify-end mt-1">
                        <a href="product.php?op=edit&id=<?php echo $id ?>" class="font-semibold bg-yellow-500 px-2 py-1 rounded-md mr-2 hover:bg-yellow-600 transition duration-200 text-white">Edit</a>
                        <a href="product.php?op=delete&id=<?php echo $id ?>" onclick="return confirm('Are you sure ?')" class="bg-red-500 font-semibold px-2 py-1 rounded-md text-white hover:bg-red-600 transition duration-200">Delete</a>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>
<?php include_once("mainfooter.php"); ?>