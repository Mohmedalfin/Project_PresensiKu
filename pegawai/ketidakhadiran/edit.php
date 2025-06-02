<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
    exit;
} else if ($_SESSION["role"] != 'pegawai') {
    header("Location: ../../auth/login.php?pesan=tolak_akses");
    exit;
}

$judul = "Edit Data Pengajuan Ketidakhadiran";
include('../layout/header.php');
include_once('../../config.php');

if (isset($_POST['update'])) {
    $pesan_kesalahan = [];

    $id = $_POST['id'];
    $keterangan = $_POST['keterangan'];
    $tanggal = $_POST['tanggal'];
    $deskripsi = $_POST['deskripsi'];

    if ($_FILES['file_baru']['error'] === 4) {
        $file_lama = $_POST['file_lama'];
    } else {
        $nama_file = null;
        $file = $_FILES['file_baru'];
        $nama_file = time() . '_' . basename($file['name']);
        $file_tmp = $file['tmp_name'];
        $ukuran_file = $file['size'];
        $ekstensi = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
        $ekstensi_diperbolehkan = ['jpg', 'jpeg', 'png', 'pdf'];
        $maks_ukuran = 10 * 1024 * 1024; // 2MB

        if (!in_array($ekstensi, $ekstensi_diperbolehkan)) {
            $pesan_kesalahan[] = "Ekstensi file harus jpg, jpeg, png dan pdf";
        } elseif ($ukuran_file > $maks_ukuran) {
            $pesan_kesalahan[] = "Ukuran file maksimal 2MB.";
        } else {
            move_uploaded_file($file_tmp, '../../presensi/img/data_izin/' . $nama_file);
        }
    }

    // Validasi input wajib
    if (empty($keterangan)) {
        $pesan_kesalahan[] = "Keterangan tidak boleh kosong.";
    }
    if (empty($tanggal)) {
        $pesan_kesalahan[] = "Tanggal tidak boleh kosong.";
    }
    if (empty($deskripsi)) {
        $pesan_kesalahan[] = "Deskripsi tidak boleh kosong.";
    }
    if ($_FILES['file_baru']['error'] !== 4) {
        if (!in_array($ekstensi, $ekstensi_diperbolehkan)) {
            $pesan_kesalahan[] = "Ekstensi file harus jpg, jpeg, png dan pdf";
        } elseif ($ukuran_file > $maks_ukuran) {
            $pesan_kesalahan[] = "Ukuran file maksimal 2MB.";
        } else {
            move_uploaded_file($file_tmp, '../../presensi/img/data_izin/' . $nama_file);
        }
    }

    if (empty($pesan_kesalahan)) {
        $user = "UPDATE ketidakhadiran SET keterangan = '$keterangan', deskripsi = '$deskripsi'
        , tanggal = '$tanggal', file = 'nama_file' WHERE id = '$id'";

        $result1 = mysqli_query($connection, $user);

        if ($result1) {
            $_SESSION['berhasil'] = 'Data pengajuan berhasil diupdate';
        } else {
            $_SESSION['validasi'] = 'Gagal menambahkan pengajuan: ' . mysqli_error($connection);
        }

        echo "<script>window.location.href='ketidakhadiran.php';</script>";
        exit();
    } else {
        // Simpan semua pesan error ke session
        $_SESSION['validasi'] = implode('<br>', $pesan_kesalahan);
        echo "<script>window.location.href='edit.php?id=$id';</script>";
        exit();
    }
}

$id = $_GET['id'];

$result = mysqli_query($connection, "SELECT * FROM ketidakhadiran WHERE id='$id'");
while ($data = mysqli_fetch_array($result)) {
    $keterangan = $data['keterangan'];
    $deskripsi = $data['deskripsi'];
    $file = $data['file'];
    $tanggal = $data['tanggal'];
}
?>

<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <div class="card col-md-6">
            <div class="card-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" value="<?= $_SESSION['id'] ?>" name="id_pegawai">

                    <div class="mb-3">
                        <label for="" class="mb-2">Keterangan</label>
                        <select name="keterangan" class="form-control">
                            <option value="">--- Pilih Keterangan ---</option>
                            <option <?php if ($keterangan === 'Cuti') {
                                echo 'selected';
                            } ?> value="Cuti">
                                Cuti
                            </option>
                            <option <?php if ($keterangan === 'Izin') {
                                echo 'selected';
                            } ?> value="Izin">
                                Izin
                            </option>
                            <option <?php if ($keterangan === 'Sakit') {
                                echo 'selected';
                            } ?> value="Sakit">
                                Sakit
                            </option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="" class="mb-2">Deskripsi Ketidakhadiran</label>
                        <textarea name="deskripsi" id="" cols="30" rows="5" class="form-control">
                            <?= $deskripsi ?>
                        </textarea>
                    </div>
                    <div class="mb-3">
                        <label for="" class="mb-2">Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" value="<?= $tanggal ?>">
                    </div>
                    <div class="mb-3">
                        <label for="" class="mb-2">Surat keterangan</label>
                        <input type="file" class="form-control" name="file_baru">
                        <input type="hidden" class="form-control" name="file" value="<?= $file ?>">
                    </div>
                    <input type="hidden" name="id" value="<?= htmlspecialchars($_GET['id']) ?>">


                    <button type="submit" class="btn btn-primary" name="update">Ajukan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include('../layout/footer.php'); ?>