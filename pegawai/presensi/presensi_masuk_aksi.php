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

$id_pegawai = $_POST['id'];
$tanggal_masuk = $_POST['tanggal_masuk'];
$jam_masuk = $_POST['jam_masuk'];
$file_foto = $_POST['photo'];



$foto = $file_foto;
$foto = str_replace('data:image/jpeg;base64,', '', $foto);
$foto = str_replace(' ', '+', $foto);
$data = base64_decode($foto);

if (!is_dir('foto')) {
    mkdir('foto', 0777, true);
}

$nama_file = 'foto/masuk_' . date('Ymd_His') . '.png';
$file = 'masuk_' . date('Ymd_His') . '.png';
if ($data === false || strlen($data) < 100) {
    die("Base64 decode gagal atau data kosong!");
}
if (file_put_contents($nama_file, $data) === false) {
    die("Gagal menyimpan file ke $nama_file");
}


file_put_contents($nama_file, $data);

$result = mysqli_query($connection, "INSERT INTO `presensi`(`id_pegawai`, `tanggal_masuk`, `jam_masuk`, `foto_masuk`) VALUES ('$id_pegawai','$tanggal_masuk','$jam_masuk','$file')");

if ($result) {
    $_SESSION['berhasil'] = "Presensi masuk berhasil";
} else {
    $_SESSION['Gagal'] = "Presensi masuk tidak berhasil";
}
?>