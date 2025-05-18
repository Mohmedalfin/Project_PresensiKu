<?php
session_start();
ob_start();
if (!isset($_SESSION["login"])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
    exit;
} else if ($_SESSION["role"] != 'admin') {
    header("Location: ../../auth/login.php?pesan=tolak_akses");
    exit;
}

$judul = "Tambah Lokasi Presensi";
include('../layout/header.php');
require_once('../../config.php');

if (isset($_POST['submit'])) {
    $nama_lokasi = htmlspecialchars($_POST['nama_lokasi']);
    $alamat_lokasi = htmlspecialchars($_POST['alamat_lokasi']);
    $tipe_lokasi = htmlspecialchars($_POST['tipe_lokasi']);
    $latitude = htmlspecialchars($_POST['latitude']);
    $longitude = htmlspecialchars($_POST['longitude']);
    $radius = htmlspecialchars($_POST['radius']);
    $zona_waktu = htmlspecialchars($_POST['zona_waktu']);
    $jam_masuk = htmlspecialchars($_POST['jam_masuk']);
    $jam_pulang = htmlspecialchars($_POST['jam_pulang']);

    // Validasi wajib isi
    if (
        empty($nama_lokasi) || empty($alamat_lokasi) || empty($tipe_lokasi) ||
        empty($latitude) || empty($longitude) || empty($radius) ||
        empty($zona_waktu) || empty($jam_masuk) || empty($jam_pulang)
    ) {
        $_SESSION['validasi'] = "Semua field wajib diisi";
        header("Location: " . base_url('admin/data_lokasi_presensi/tambah.php'));
        exit();
    }

    $result = mysqli_query($connection, "INSERT INTO lokasi_presensi 
        (nama_lokasi, alamat_lokasi, tipe_lokasi, latitude, longitude, radius, zona_waktu, jam_masuk, jam_pulang) 
        VALUES 
        ('$nama_lokasi', '$alamat_lokasi', '$tipe_lokasi', '$latitude', '$longitude', '$radius', '$zona_waktu', '$jam_masuk', '$jam_pulang')");

    if ($result) {
        $_SESSION['berhasil'] = 'Data berhasil ditambahkan';
    } else {
        $_SESSION['validasi'] = 'Gagal menambahkan data: ' . mysqli_error($connection);
    }

    header("Location: " . base_url('admin/data_lokasi_presensi/lokasi_presensi.php'));
    exit();
}


?>

<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <div class="card col-md-6">
            <div class="card-body">
                <form action="<?= base_url('admin/data_lokasi_presensi/tambah.php') ?>" method="POST">
                    <div class="md-3">
                        <label for="" class="mb-1">Nama Lokasi</label>
                        <input type="text" class="form-control" name="nama_lokasi" id="nama_lokasi" value="<?php if (isset($_POST['nama_lokasi']))
                            echo $_POST['nama_lokasi'] ?>" placeholder="Masukkan nama lokasi" required>
                    </div>
                    <div class="md-3 mt-2">
                        <label for="" class="mb-1">ALamat Lokasi</label>
                        <input type="text" class="form-control" name="alamat_lokasi" id="alamat_lokasi" value="<?php if (isset($_POST['alamat_lokasi']))
                            echo $_POST['alamat_lokasi'] ?>" placeholder="Masukkan alamat lokasi" required>
                    </div>
                    <div class="md-3 mt-2">
                        <label for="" class="mb-1">Tipe Lokasi</label>
                        <select name="tipe_lokasi" class="form-control" id="">
                            <option value="">--- Pilih Tipe Lokasi ---</option>
                            <option value="Pusat">
                                Pusat
                            </option>
                            <option value="Cabang">
                                Cabang
                            </option>
                        </select>
                    </div>
                    <div class="md-3 mt-2">
                        <label for="" class="mb-1">Latitude</label>
                        <input type="text" class="form-control" name="latitude" id="latitude" value="<?php if (isset($_POST['latidude']))
                            echo $_POST['latitude'] ?>" placeholder="Masukkan Latitude" required>
                    </div>
                    <div class="md-3 mt-2">
                        <label for="" class="mb-1">Longitude</label>
                        <input type="text" class="form-control" name="longitude" id="longitude" value="<?php if (isset($_POST['longitude']))
                            echo $_POST['longitude'] ?>" placeholder="Masukkan Longitude" required>
                    </div>
                    <div class="md-3 mt-2">
                        <label for="" class="mb-1">Radius</label>
                        <input type="text" class="form-control" name="radius" id="radius" value="<?php if (isset($_POST['radius']))
                            echo $_POST['radius'] ?>" placeholder="Masukkan Jarak Radius" required>
                    </div>
                    <div class="md-3 mt-2">
                        <label for="" class="mb-1">Zona Waktu</label>
                        <select name="zona_waktu" class="form-control" id="">
                            <option value="">--- Pilih Zona Waktu---</option>
                            <option value="WIB">WIB</option>
                            <option value="WITA">WITA</option>
                            <option value="WIt">WIT</option>
                        </select>
                    </div>
                    <div class="md-3 mt-2">
                        <label for="" class="mb-1">Jam Masuk</label>
                        <input type="time" class="form-control" name="jam_masuk" id="jam_masuk"
                            placeholder="Masukkan Jam Masuk" required>
                    </div>
                    <div class="md-3 mt-2">
                        <label for="" class="mb-1">Jam Pulang</label>
                        <input type="time" class="form-control" name="jam_pulang" id="jam_pulang"
                            placeholder="Masukkan Jam Pulang" required>
                    </div>

                    <button class="btn btn-primary mt-3" name="submit" type="submit">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div><?php include('../layout/footer.php'); ?>