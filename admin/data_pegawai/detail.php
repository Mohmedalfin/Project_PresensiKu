<?php
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
    exit;
} else if ($_SESSION["role"] != 'admin') {
    header("Location: ../../auth/login.php?pesan=tolak_akses");
    exit;
}

$judul = "Detail Data Pegawai";
include('../layout/header.php');
require_once('../../config.php');

$id = $_GET['id'];
$result = mysqli_query($connection, "SELECT users.id_pegawai, users.username, users.password, users.status, users.role, pegawai.* FROM users JOIN pegawai ON users.id_pegawai = pegawai.id WHERE pegawai.id =$id");
?>

<?php while ($pegawai = mysqli_fetch_array($result)): ?>

<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <td>Nama </td>
                                <td>:
                                    <?= $pegawai['name'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Jenis Kelamin </td>
                                <td>:
                                    <?= $pegawai['jenis_kelamin'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Alamat </td>
                                <td>:
                                    <?= $pegawai['alamat'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td>No. Handphone </td>
                                <td>:
                                    <?= $pegawai['no_handphone'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Jabatan </td>
                                <td>:
                                    <?= $pegawai['jabatan'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Username </td>
                                <td>:
                                    <?= $pegawai['username'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Role </td>
                                <td>:
                                    <?= $pegawai['role'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Lokasi Presensi</td>
                                <td>:
                                    <?= $pegawai['lokasi_presensi'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>:
                                    <?= $pegawai['status'] ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <img src="<?= base_url('presensi/img/foto_pegawai/' . $pegawai['foto']) ?>" alt="Foto Pegawai"
                    style="width: 100%; max-width: 300px; height: 300px; object-fit: cover; margin-left: 60px; border-radius: 8px; border: 1px solid rgb(68, 68, 68)">
            </div>
        </div>
    </div>
</div>

<?php
endwhile;
?>

<?php include('../layout/footer.php'); ?>