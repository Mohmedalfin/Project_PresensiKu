<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
    exit;
} else if ($_SESSION["role"] != 'pegawai') {
    header("Location: ../../auth/login.php?pesan=tolak_akses");
    exit;
}


include('../layout/header.php');
include_once('../../config.php');


$lokasi_presensi = $_SESSION['lokasi_presensi'];
$result = mysqli_query($connection, "SELECT * FROM lokasi_presensi WHERE nama_lokasi = '$lokasi_presensi'");


?>