<?php
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
    exit;
} else if ($_SESSION["role"] != 'admin') {
    header("Location: ../../auth/login.php?pesan=tolak_akses");
    exit;
}

$judul = "Data Lokasi Presensi";
include('../layout/header.php');
require_once('../../config.php');

$result = mysqli_query($connection, "SELECT * FROM lokasi_presensi ORDER BY id DESC");
?>


<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <a href="<?= base_url('admin/data_lokasi_presensi/tambah.php?id=') ?>" class="btn btn-primary"><span
                class="text"><i class="fa-solid fa-folder-plus p-1"></i>Tambah Data</span></a>
        <!-- Table -->
        <table class="table table-bordered mt-3">
            <tr class="text-center">
                <th>No.</th>
                <th>Nama Lokasi</th>
                <th>Alamat Lokasi</th>
                <th>Latitude/Longitude</th>
                <th>Radius</th>
                <th>Aksi</th>
            </tr>

            <?php if (mysqli_num_rows($result) === 0): ?>
            <tr>
                <td colspan="6">Data kosong, silahkan tambahkan data baru</td>
            </tr>
            <?php else: ?>
            <?php $no = 1;
                while ($lokasi = mysqli_fetch_array($result)): ?>

            <tr class="text-center">
                <td><?= $no++ ?></td>
                <td><?= $lokasi['nama_lokasi'] ?></td>
                <td><?= $lokasi['alamat_lokasi'] ?></td>
                <td><?= $lokasi['latitude'] . '/' . $lokasi['longitude'] ?></td>
                <td><?= $lokasi['radius'] ?></td>
                <td class="text-center">
                    <a href="<?= base_url('admin/data_lokasi_presensi/detail.php?id=' . $lokasi['id']) ?>"
                        class="badge badge-pill bg-primary">Details</a>
                    <a href="<?= base_url('admin/data_lokasi_presensi/edit.php?id=' . $lokasi['id']) ?>"
                        class="badge badge-pill bg-primary">Edit</a>
                    <a href="<?= base_url('admin/data_lokasi_presensi/hapus.php?id=' . $lokasi['id']) ?>"
                        class="badge badge-pill bg-danger tombol-hapus ">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>

            <!-- di sini nanti isi data baris jika ada -->
            <?php endif; ?>
        </table>
    </div>
</div>

<?php include('../layout/footer.php'); ?>