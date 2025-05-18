<?php
session_start();
require_once('../../config.php');

// Cek apakah parameter id ada
if (!isset($_GET['id'])) {
    $_SESSION['validasi'] = "ID tidak ditemukan dalam permintaan";
    header("Location: lokasi_presensi.php");
    exit;
}

$id = $_GET['id'];
$id = mysqli_real_escape_string($connection, $id);

$query = "DELETE FROM lokasi_presensi WHERE id = '$id'";
$result = mysqli_query($connection, $query);

if ($result) {
    $_SESSION['berhasil'] = "Data lokasi berhasil dihapus";
} else {
    $_SESSION['validasi'] = "Gagal menghapus data: " . mysqli_error($connection);
}

header("Location: lokasi_presensi.php");
exit;
?>