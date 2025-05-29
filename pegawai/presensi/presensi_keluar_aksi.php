<?php
ob_start();
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
    exit;
} else if ($_SESSION["role"] != 'pegawai') {
    header("Location: ../../auth/login.php?pesan=tolak_akses");
    exit;
}

include_once('../../config.php');

$id_presensi = $_POST['id'];
$tanggal_keluar = $_POST['tanggal_keluar'];
$jam_keluar = $_POST['jam_keluar'];
$file_foto = $_POST['photo'];

$foto = $file_foto;
$foto = str_replace('data:image/jpeg;base64,', '', $foto);
$foto = str_replace(' ', '+', $foto);
$data = base64_decode($foto);

if (!is_dir('foto')) {
    mkdir('foto', 0777, true);
}

$nama_file = 'foto/keluar_' . date('Ymd_His') . '.png';
$file = 'keluar_' . date('Ymd_His') . '.png';
if ($data === false || strlen($data) < 100) {
    die("Base64 decode gagal atau data kosong!");
}
if (file_put_contents($nama_file, $data) === false) {
    die("Gagal menyimpan file ke $nama_file");
}


file_put_contents($nama_file, $data);

$result = mysqli_query($connection, "UPDATE `presensi` SET `tanggal_keluar`='$tanggal_keluar',`jam_keluar`='$jam_keluar',`foto_keluar`='$file' WHERE id = $id_presensi");

if ($result) {
    $_SESSION['berhasil'] = "Presensi keluar berhasil";
} else {
    $_SESSION['Gagal'] = "Presensi keluar tidak berhasil";
}
?>