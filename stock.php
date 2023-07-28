<?php
$host = "localhost:3307";
$user = "root";
$pass = "";
$db = "bookkeeping";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) { //cek koneksi
    die("Tidak bisa terkoneksi ke database");
}

$tanggal = "";
$supplier = "";
$nama = "";
$kategori = "";
$harga = "";
$stok = "";
$berat = "";
$error = "";
$sukses = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if($op == 'delete'){
    $id = $_GET['id'];
    $sql3 = "delete from stock where id = '$id'";
    $q3 = mysqli_query($koneksi, $sql3);
    if ($q3) {
        $sukses = "Data berhasil di delete";
    } else {
        $error = "Data gagal di delete";
    }
}

if ($op == 'edit') {
    $id = $_GET['id'];
    $sql3 = "select * from stock where id = '$id'";
    $q3 = mysqli_query($koneksi, $sql3);
    $r3 = mysqli_fetch_array($q3);
    $tanggal = $r3['tanggal_masuk'];
    $supplier = $r3['supplier'];
    $nama = $r3['nama'];
    $kategori = $r3['kategori'];
    $harga = $r3['harga'];
    $stok = $r3['stok'];
    $berat = $r3['berat'];

    if ($tanggal == '') {
        $error = "Data not found";
    }
}

if (isset($_POST['simpan'])) {
    $tanggal = $_POST['tanggal'];
    $supplier = $_POST['supplier'];
    $nama = $_POST['nama'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $berat = $_POST['berat'];

    if ($tanggal && $supplier && $nama && $kategori && $harga && $stok && $berat) {
        if ($op == 'edit') {
            $sql1 = "update stock set tanggal_masuk = '$tanggal', supplier = '$supplier', nama = '$nama', kategori = '$kategori', harga = '$harga', stok = '$stok', berat = '$berat' where id = '$id'";
            $q1 = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses = "Data berhasil di update";
            } else {
                $error = "Data gagal diupdate";
            }
        } else {
            $sql1 = "insert into stock(tanggal_masuk, supplier, nama, kategori, harga, stok, berat) values ('$tanggal', '$supplier', '$nama', '$kategori', '$harga', '$stok', '$berat')";
            $q1 = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $sukses = "Berhasil memasukkan data baru";
            } else {
                $error = "Gagal memasukkan data";
            }
        }
    } else {
        $error = "Masukkan data terlebih dahulu";
    }
}
?>




<?php include_once("mainheader.php"); ?>
<div class="mt-32">
    <div class="container">
        <h1 class="text-5xl font-bold text-slate-200">Stock</h1>
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
                    header("refresh:5;url=stock.php");
                }
                ?>
                <?php
                if ($sukses) {
                    ?>
                    <div class="w-full bg-green-100 py-2 px-3 mb-2">
                        <?php echo $sukses ?>
                    </div>
                    <?php
                    header("refresh:5;url=stock.php");
                }
                ?>
                <!-- form -->
                <form action="" method="POST" class="bg-teal-950">
                    <div class="mb-3 flex flex-col md:flex-row items-center">
                        <label for="tanggal" class="w-1/6 col-form-label font-bold text-white">Date</label>
                        <div class="w-5/6">
                            <input type="date" class="rounded-lg border py-2 px-4 w-full" name="tanggal" id="tanggal"
                                value="<?php echo $tanggal ?>">
                        </div>
                    </div>
                    <div class="mb-5 flex flex-col md:flex-row items-center">
                        <label for="supplier" class="w-1/6 col-form-label font-bold text-white">Supplier</label>
                        <div class="w-5/6">
                            <input type="text" class="rounded-lg border py-2 px-4 w-full" name="supplier" id="supplier"
                                value="<?php echo $supplier ?>">
                        </div>
                    </div>
                    <div class="mb-5 flex flex-col md:flex-row items-center">
                        <label for="nama" class="w-1/6 col-form-label font-bold text-white">Product</label>
                        <div class="w-5/6">
                            <input type="text" class="rounded-lg border py-2 px-4 w-full" name="nama" id="nama"
                                value="<?php echo $nama ?>">
                        </div>
                    </div>
                    <div class="mb-3 flex flex-col md:flex-row items-center">
                        <label for="kategori" class="w-1/6 col-form-label font-bold text-white">Category</label>
                        <div class="w-5/6">
                            <select name="kategori" id="kategori" class="rounded-lg border py-2 px-4 w-full">
                                <option value="">-Chooce category</option>
                                <option value="Foodstuffs" <?php if ($kategori == "Foodstuffs")
                                    echo "selected" ?>>Foodstuffs</option>
                                    <option value="Household Purposes" <?php if ($kategori == "Household Purposes")
                                    echo "selected" ?>>Household Purposes</option>
                                    <option value="Cigarette" <?php if ($kategori == "Cigarette")
                                    echo "selected" ?>>Cigarette
                                    </option>
                                </select>
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
                    <div>
                        <input type="submit" name="simpan" value="Simpan Data"
                            class="w-1/6 px-2 py-3 bg-blue-600 text-white font-bold text-center rounded-lg hover:bg-blue-700 transition duration-200">
                    </div>
                </form>
            </div>
        </div>
        <div class="flex flex-wrap mx-4 justify-center">
            <?php
            $sql2 = "select * from stock order by id";
            $q2 = mysqli_query($koneksi, $sql2);
            $urut = 1;
            while ($r2 = mysqli_fetch_array($q2)) {
                $id = $r2['id'];
                $tanggal = $r2['tanggal_masuk'];
                $supplier = $r2['supplier'];
                $nama = $r2['nama'];
                $kategori = $r2['kategori'];
                $harga = $r2['harga'];
                $stok = $r2['stok'];
                $berat = $r2['berat'];
                ?>
                <div
                    class="w-1/4 border border-teal-300 rounded-lg py-4 px-6 mx-4 my-4 shadow-2xl hover:bg-teal-900 transition duration-200">
                    <div class="flex items-center">
                        <div class="w-3/4">
                            <p class="text-sm text-white">
                                <?php echo $supplier ?>
                            </p>
                            <p class="text-3xl font-bold text-slate-300 pt-1">
                                <?php echo $nama ?>
                            </p>
                            <p class="text-md mb-1 font-semibold text-white">
                                <?php echo $kategori ?>
                            </p>
                            <p class="text-sm text-white">
                                <?php echo $tanggal ?>
                            </p>
                            <p class="text-sm text-white">Rp.
                                <?php echo $harga ?>
                            </p>
                        </div>
                        <div class="w-1/4">
                            <p class="text-base text-white text-center">Stok : </p>
                            <p class="text-5xl font-bold text-slate-300 py-1 text-center">
                                <?php echo $stok ?>
                            </p>
                        </div>
                    </div>
                    <div class="flex justify-end mt-1">
                        <a href="stock.php?op=edit&id=<?php echo $id ?>" class="font-semibold bg-yellow-500 px-2 py-1 rounded-md mr-2 hover:bg-yellow-600 transition duration-200 text-white">Edit</a>
                        <a href="stock.php?op=delete&id=<?php echo $id ?>" onclick="return confirm('Are you sure ?')" class="bg-red-500 font-semibold px-2 py-1 rounded-md text-white hover:bg-red-600 transition duration-200">Delete</a>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>
<?php include_once("mainfooter.php"); ?>