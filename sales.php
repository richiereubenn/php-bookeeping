<?php
$host = "localhost:3307";
$user = "root";
$pass = "";
$db = "bookkeeping";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) { //cek koneksi
    die("Tidak bisa terkoneksi ke database");
}
$kuantitas = '';
$error = '';
$sukses = '';

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($op == 'edit') {
    $id = $_GET['id'];
    $qsSalesU = mysqli_query($koneksi, "select * from sales where id = '$id'");
    $rSales = mysqli_fetch_array($qsSalesU);
    $nama = $rSales['nama'];
    $kuantitas = $rSales['kuantitas'];
    $total_harga = $rSales['total_harga'];
    $qsProduct = mysqli_query($koneksi, "select stok from product where nama = '$nama'");
    $rProduct = mysqli_fetch_array($qsProduct);
    $stok = $rProduct['stok'];
    $updateStokTambah = $stok + $kuantitas;
    if (isset($_POST['simpan'])) {
        $quProduct = mysqli_query($koneksi, "update product set stok = '$updateStokTambah' where nama = '$nama'");
    }
    if ($nama == '') {
        $error = "Data not found";
    }
}

if ($op == 'delete') {
    $id = $_GET['id'];
    $qsSales = mysqli_query($koneksi, "select nama, kuantitas from sales where id = '$id'");
    $rSales = mysqli_fetch_array($qsSales);
    $nama = $rSales['nama'];
    $kuantitas = $rSales['kuantitas'];

    $qsProduct = mysqli_query($koneksi, "select stok from product where nama = '$nama'");
    $rProduct = mysqli_fetch_array($qsProduct);
    $stok = $rProduct['stok'];
    $updateStok = $stok + $kuantitas;

    $quProduct = mysqli_query($koneksi, "update product set stok = '$updateStok' where nama = '$nama'");
    $qdSales = mysqli_query($koneksi, "delete from sales where id = '$id'");
    if ($qdSales) {
        $sukses = "Data successfully deleted";
    } else {
        $error = "Data failed to delete";
    }
}

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $kuantitas = $_POST['kuantitas'];
    $qsProduct = mysqli_query($koneksi, "select harga, stok from product where nama = '$nama'");
    $rProduct = mysqli_fetch_array($qsProduct);
    $harga = $rProduct['harga'];
    $stok = $rProduct['stok'];
    $total_harga = $kuantitas * $harga;
    $updateStokKurang = $stok - $kuantitas;

    if ($nama && $kuantitas && $stok >= $kuantitas) {
        if ($op == 'edit') {
            $quSales = mysqli_query($koneksi, "update sales set kuantitas = '$kuantitas', total_harga = '$total_harga' where id = '$id'");
            $quProduct = mysqli_query($koneksi, "update product set stok = '$updateStokKurang' where nama = '$nama'");
            if ($quSales) {
                $sukses = "Data successfully updated";
            } else {
                $error = "Data failed to update";
            }
        } else {
            $qcSales = mysqli_query($koneksi, "insert into sales(nama, kuantitas, total_harga) values ('$nama', '$kuantitas', '$total_harga')");
            $quProduct = mysqli_query($koneksi, "update product set stok = '$updateStokKurang' where nama = '$nama'");
            if ($qcSales) {
                $sukses = "Successfully add new data";
            } else {
                $error = "Failed to enter data";
            }

        }
    } else {
        $error = "Stock is not enough";
    }
}
?>

<?php include_once("mainheader.php"); ?>
<div class="mt-32">
    <div class="container">
        <h1 class="text-5xl font-bold text-slate-200">Sales</h1>
    </div>
</div>
<div class="mt-8">
    <div class="container">
        <div class="flex">
            <div class="w-3/4">
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
                            header("refresh:5;url=sales.php");
                        }
                        ?>
                        <?php
                        if ($sukses) {
                            ?>
                            <div class="w-full bg-green-100 py-2 px-3 mb-2">
                                <?php echo $sukses ?>
                            </div>
                            <?php
                            header("refresh:5;url=sales.php");
                        }
                        ?>
                        <form action="" method="POST" class="bg-teal-950">
                            <div class="mb-3 flex flex-col md:flex-row items-center">
                                <label for="nama" class="w-1/6 col-form-label font-bold text-white">Product Name</label>
                                <div class="w-5/6">
                                    <select name="nama" id="nama" class="rounded-lg border py-2 px-4 w-full">
                                        <?php
                                        $qsProduct = mysqli_query($koneksi, "select nama from product order by id");
                                        while ($rProduct = mysqli_fetch_array($qsProduct)) {
                                            $id = $rProduct['id'];
                                            $nama = $rProduct['nama'];
                                            ?>
                                            <option value="<?php echo $nama ?>"><?php echo $nama ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-5 flex flex-col md:flex-row items-center">
                                <label for="kuantitas"
                                    class="w-1/6 col-form-label font-bold text-white">Quantity</label>
                                <div class="w-5/6">
                                    <input type="text" class="rounded-lg border py-2 px-4 w-full" name="kuantitas"
                                        id="kuantitas" value="<?php echo $kuantitas ?>">
                                </div>
                            </div>
                            <div>
                                <input type="submit" name="simpan" value="Simpan Data" class="w-1/6 px-2 py-3 bg-blue-600 text-white font-bold text-center rounded-lg hover:bg-blue-700 transition duration-200">
                                <a href="#salRecap" class="w-1/6 px-4 py-3 ml-3 bg-cyan-600 text-white font-bold text-center rounded-lg hover:bg-cyan-700 transition duration-200">See Recap</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class ="w-1/4">
            <div class="text-white border border-teal-300 rounded-lg hover:bg-teal-900 transition duration-200">
                <?php
                    $qsSumPenjualan = mysqli_query($koneksi, "select SUM(total_harga) as total_pemasukan from sales");
                    $rSumPenjualan = mysqli_fetch_array($qsSumPenjualan);
                    $sumPenjualan = $rSumPenjualan['total_pemasukan'];
                ?>
                <div class="p-5 mx-auto">
                    <h1 class="text-slate-200 font-semibold text-md">Current Total Income : </h1>
                    <p class="text-slate-300 font-bold text-4xl">Rp. <?php echo $sumPenjualan?></p>
                </div>
            </div>
            <div class="text-white border border-teal-300 rounded-lg mt-5 hover:bg-teal-900 transition duration-200">
                <?php
                    $qsAvgPenjualan = mysqli_query($koneksi, "select ROUND(AVG(total_harga)) as avg_pemasukan from sales");
                    $rAvgPenjualan = mysqli_fetch_array($qsAvgPenjualan);
                    $avgPenjualan = $rAvgPenjualan['avg_pemasukan'];
                ?>
                <div class="p-5">
                    <h1 class="text-slate-200 font-semibold text-md">Current Average Income : </h1>
                    <p class="text-slate-300 font-bold text-4xl">Rp. <?php echo $avgPenjualan?></p>
                </div>
            </div>
            </div>
        </div>

        <!-- nampilin data -->
        <h1 class="text-3xl font-bold text-slate-200 mt-64 mb-10 text-center" id="salRecap">Sales Recap</h1>
        <div class="bg-white mb-10 shadow-md rounded-lg overflow-hidden max-w-4xl mx-auto border border-white">
            <div class="px-4 py-2 bg-teal-900 text-white">
                Sales
            </div>
            <div class="w-full overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="border-b px-2 py-2 bg-teal-800 text-white">#</th>
                            <th class="border-b px-2 py-2 bg-teal-800 text-white">Product</th>
                            <th class="border-b px-2 py-2 bg-teal-800 text-white">Quantity</th>
                            <th class="border-b px-2 py-2 bg-teal-800 text-white">Total Prize</th>
                            <th class="border-b px-2 py-2 bg-teal-800 text-white">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql2 = "select * from sales order by id";
                        $q2 = mysqli_query($koneksi, $sql2);
                        $urut = 1;
                        while ($r2 = mysqli_fetch_array($q2)) {
                            $id = $r2['id'];
                            $nama = $r2['nama'];
                            $kuantitas = $r2['kuantitas'];
                            $total_harga = $r2['total_harga'];
                            ?>
                            <tr>
                                <th class="border-b bg-teal-950 text-white px-2 py-2 text-center ">
                                    <?php echo $urut++ ?>
                                </th>
                                <td class="border-b bg-teal-950 text-white px-2 py-2 text-center">
                                    <?php echo $nama++ ?>
                                </td>
                                <td class="border-b bg-teal-950 text-white px-2 py-2 text-center">
                                    <?php echo $kuantitas++ ?>
                                </td>
                                <td class="border-b bg-teal-950 text-white px-2 py-2 text-center">
                                    <?php echo $total_harga++ ?>
                                </td>
                                <td class="border-b bg-teal-950 text-white py-2 flex justify-center">
                                    <a href="sales.php?op=edit&id=<?php echo $id ?>"
                                        class="font-semibold bg-yellow-500 px-4 py-2 rounded-lg mr-2 hover:bg-yellow-600 transition duration-200 text-white">Edit</a>
                                    <a href="sales.php?op=delete&id=<?php echo $id ?>"
                                        class="bg-red-500 font-semibold px-4 py-2 rounded-lg text-white"
                                        onclick="return confirm('Are you sure ?')">Cancel</a>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once("mainfooter.php"); ?>