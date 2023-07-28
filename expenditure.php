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
$kategori = "";
$keterangan = "";
$jumlah = "";
$error = "";
$sukses = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($op == 'edit') {
    $id = $_GET['id'];
    $qsExpend = mysqli_query($koneksi, "select * from expenditure where id = '$id'");
    $rExpend = mysqli_fetch_array($qsExpend);
    $tanggal = $rExpend['tanggal'];
    $kategori = $rExpend['kategori'];
    $keterangan = $rExpend['keterangan'];
    $jumlah = $rExpend['jumlah'];

    if ($tanggal == '') {
        $error = "Data not found";
    }
}

if ($op == 'delete') {
    $id = $_GET['id'];
    $qdExpend = mysqli_query($koneksi, "delete from expenditure where id = '$id'");
    if ($qdExpend) {
        $sukses = "Data successfully deleted";
    } else {
        $error = "Data failed to delete";
    }
}


if (isset($_POST['simpan'])) { //untuk create
    $tanggal = $_POST['tanggal'];
    $kategori = $_POST['kategori'];
    $keterangan = $_POST['keterangan'];
    $jumlah = $_POST['jumlah'];

    if ($tanggal && $keterangan && $kategori && $jumlah) {
        if ($op == 'edit') {
            $quExpend = mysqli_query($koneksi, "update expenditure set tanggal = '$tanggal', kategori = '$kategori', keterangan = '$keterangan', jumlah = '$jumlah' where id = '$id'");
            if ($quExpend) {
                $sukses = "Data successfully updated";
            } else {
                $error = "Data failed to update";
            }
        } else {
            $qcExpend = mysqli_query($koneksi, "insert into expenditure(tanggal, kategori, keterangan, jumlah) values ('$tanggal', '$kategori', '$keterangan', '$jumlah')");
            if ($qcExpend) {
                $sukses = "Successfully add new data";
            } else {
                $error = "Failed to enter data";
            }
        }
    } else {
        $error = "Enter data first";
    }
}
?>
<?php include_once("mainheader.php"); ?>
<div class="mt-32">
    <div class="container">
        <h1 class="text-5xl font-bold text-slate-200">Expenditure</h1>
    </div>
</div>
<div class="mt-12">
    <div class="container">
        <div class="flex w-full">
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
                        }
                        ?>
                        <?php
                        if ($sukses) {
                            ?>
                            <div class="w-full bg-green-100 py-2 px-3 mb-2">
                                <?php echo $sukses ?>
                            </div>
                            <?php
                        }
                        ?>
                        <!-- form -->
                        <form action="" method="POST" class="bg-teal-950">
                            <div class="mb-3 flex flex-col md:flex-row items-center">
                                <label for="tanggal" class="w-1/6 col-form-label font-bold text-white">Date</label>
                                <div class="w-5/6">
                                    <input type="date" class="rounded-lg border py-2 px-4 w-full" name="tanggal"
                                        id="tanggal" value="<?php echo $tanggal ?>">
                                </div>
                            </div>
                            <div class="mb-3 flex flex-col md:flex-row items-center">
                                <label for="kategori" class="w-1/6 col-form-label font-bold text-white">Category</label>
                                <div class="w-5/6">
                                    <select name="kategori" id="kategori" class="rounded-lg border py-2 px-4 w-full">
                                        <option value="">-Chooce category</option>
                                        <option value="Fixed Exspense">
                                                Fixed Exspense</option>
                                            <option value="Variable Exspense">Variabel Expense</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3 flex flex-col md:flex-row items-center">
                                    <label for="keterangan"
                                        class="w-1/6 col-form-label font-bold text-white">Statement</label>
                                    <div class="w-5/6">
                                        <input type="text" class="rounded-lg border py-2 px-4 w-full" name="keterangan"
                                            id="keterangan" value="<?php echo $keterangan ?>">
                                </div>
                            </div>
                            <div class="mb-5 flex flex-col md:flex-row items-center">
                                <label for="jumlah" class="w-1/6 col-form-label font-bold text-white">Amount</label>
                                <div class="w-5/6">
                                    <input type="text" class="rounded-lg border py-2 px-4 w-full" name="jumlah"
                                        id="jumlah" value="<?php echo $jumlah ?>">
                                </div>
                            </div>
                            <div>
                                <input type="submit" name="simpan" value="Save Data" class="w-1/6 px-2 py-3 bg-blue-600 text-white font-bold text-center rounded-lg hover:bg-blue-700 transition duration-200">
                                <a href="#exRecap" class="w-1/6 px-4 py-3 ml-3 bg-cyan-600 text-white font-bold text-center rounded-lg hover:bg-cyan-700 transition duration-200">See Recap</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class ="w-1/4">
            <div class="text-white border border-teal-300 rounded-lg hover:bg-teal-900 transition duration-200">
                <?php
                    $qsSumPengeluaran = mysqli_query($koneksi, "select SUM(jumlah) as total_pengeluaran from expenditure");
                    $rSumPengeluaran = mysqli_fetch_array($qsSumPengeluaran);
                    $sumPengeluaran = $rSumPengeluaran['total_pengeluaran'];
                ?>
                <div class="p-5 mx-auto">
                    <h1 class="text-slate-200 font-semibold text-md">Current Total Expend : </h1>
                    <p class="text-slate-300 font-bold text-4xl">Rp. <?php echo $sumPengeluaran?></p>
                </div>
            </div>
            <div class="text-white border border-teal-300 rounded-lg mt-5 hover:bg-teal-900 transition duration-200">
                <?php
                    $qsAvgPengeluaran = mysqli_query($koneksi, "select ROUND(AVG(jumlah)) as avg_pengeluaran from expenditure");
                    $rAvgPengeluaran = mysqli_fetch_array($qsAvgPengeluaran);
                    $avgPengeluaran = $rAvgPengeluaran['avg_pengeluaran'];
                ?>
                <div class="p-5">
                    <h1 class="text-slate-200 font-semibold text-md">Current Average Expend : </h1>
                    <p class="text-slate-300 font-bold text-4xl">Rp. <?php echo $avgPengeluaran?></p>
                </div>
            </div>
            </div>
        </div>

        <!-- untuk menampilkan data -->
        <h1 class="text-3xl font-bold text-slate-200 mt-36 mb-10 text-center" id="exRecap">Expenditure Recap</h1>
        <div class="bg-white mb-10 shadow-md rounded-lg overflow-hidden max-w-4xl mx-auto border border-white">
            <div class="px-4 py-2 bg-teal-900 text-white">
                Expenditure
            </div>
            <div class="w-full overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="border-b px-2 py-2 bg-teal-800 text-white">#</th>
                            <th class="border-b px-2 py-2 bg-teal-800 text-white">Date</th>
                            <th class="border-b px-2 py-2 bg-teal-800 text-white">Category</th>
                            <th class="border-b px-2 py-2 bg-teal-800 text-white">Statement</th>
                            <th class="border-b px-2 py-2 bg-teal-800 text-white">Amount</th>
                            <th class="border-b px-2 py-2 bg-teal-800 text-white">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $qsExpend = mysqli_query($koneksi, "select * from expenditure order by id");
                        $urut = 1;
                        while ($rExpend = mysqli_fetch_array($qsExpend)) {
                            $id = $rExpend['id'];
                            $tanggal = $rExpend['tanggal'];
                            $kategori = $rExpend['kategori'];
                            $keterangan = $rExpend['keterangan'];
                            $jumlah = $rExpend['jumlah'];
                            ?>
                            <tr>
                                <th class="border-b bg-teal-950 text-white px-2 py-2 text-center ">
                                    <?php echo $urut++ ?>
                                </th>
                                <td class="border-b bg-teal-950 text-white px-2 py-2 text-center">
                                    <?php echo $tanggal++ ?>
                                </td>
                                <td class="border-b bg-teal-950 text-white px-2 py-2 text-center">
                                    <?php echo $kategori++ ?>
                                </td>
                                <td class="border-b bg-teal-950 text-white px-2 py-2 text-center">
                                    <?php echo $keterangan++ ?>
                                </td>
                                <td class="border-b bg-teal-950 text-white px-2 py-2 text-center">
                                    <?php echo $jumlah++ ?>
                                </td>
                                <td class="border-b bg-teal-950 text-white py-2 flex justify-center">
                                    <a href="expenditure.php?op=edit&id=<?php echo $id ?>"
                                        class="font-semibold bg-yellow-500 px-4 py-2 rounded-lg mr-2 hover:bg-yellow-600 transition duration-200 text-white">Edit</a>
                                    <a href="expenditure.php?op=delete&id=<?php echo $id ?>"
                                        class="bg-red-500 font-semibold px-4 py-2 rounded-lg text-white"
                                        onclick="return confirm('Are you sure ?')">Delete</a>
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