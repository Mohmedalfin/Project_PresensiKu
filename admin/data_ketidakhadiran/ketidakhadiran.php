<?php
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
    exit;
} else if ($_SESSION["role"] != 'admin') {
    header("Location: ../../auth/login.php?pesan=tolak_akses");
    exit;
}

$judul = "Data Pegawai";
include('../layout/header.php');
require_once('../../config.php');

$result = mysqli_query($connection, "SELECT * FROM ketidakhadiran ORDER BY id DESC");

?>

<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <table class="table table-bordered text-center">
            <tr>
                <th>No.</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Deskripsi</th>
                <th>File</th>
                <th>Status Pengajuan</th>
            </tr>
            <?php
            if (mysqli_num_rows($result) === 0) { ?>
            <tr>
                <td colspan="7">Data ketidakhadiran masih kosong</td>
            </tr>
            <?php } else { ?>
            <?php $no = 1;
                while ($data = mysqli_fetch_array($result)): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= date('d F Y', strtotime($data['tanggal'])) ?></td>
                <td><?= $data['keterangan'] ?></td>
                <td><?= $data['deskripsi'] ?></td>
                <td>
                    <a target="_blank" href="<?= base_url('presensi/img/data_izin/' . $data['file']) ?>"
                        class="badge badge-pill bg-primary">Download</a>
                </td>
                <td>
                    <?php if ($data['status_pengajuan'] === 'PENDING'): ?>
                    <a href="<?= base_url('admin/data_ketidakhadiran/detail.php?id=' . $data['id']) ?>"
                        class="badge badge-pill bg-warning">PENDING</a>
                    <?php elseif ($data['status_pengajuan'] === 'REJECTED'): ?>
                    <a href="<?= base_url('admin/data_ketidakhadiran/detail.php?id=' . $data['id']) ?>"
                        class="badge badge-pill bg-danger">REJECTED</a>
                    <?php else: ?>
                    <a href="<?= base_url('admin/data_ketidakhadiran/detail.php?id=' . $data['id']) ?>"
                        class="badge badge-pill bg-success">APPROVED</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
            <?php } ?>
        </table>
    </div>
</div>
<?php include('../layout/footer.php'); ?>