<?php
session_start();
if (!isset($_SESSION["login"])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
    exit;
} else if ($_SESSION["role"] != 'admin') {
    header("Location: ../../auth/login.php?pesan=tolak_akses");
    exit;
}

$judul = "Home";
include('../layout/header.php');
require_once('../../config.php'); // Pastikan koneksi `$connection` tersedia

// Total pegawai aktif
$pegawai = mysqli_query($connection, "SELECT pegawai.*, users.status 
                                      FROM pegawai 
                                      JOIN users ON pegawai.id = users.id_pegawai 
                                      WHERE status = 'Aktif'");
$total_pegawai = mysqli_num_rows($pegawai);


$tanggal_hari_ini = date('Y-m-d');

// jumlah Sakit
$query = mysqli_query($connection, "SELECT COUNT(*) as jumlah_sakit 
                                    FROM ketidakhadiran 
                                    WHERE tanggal = '$tanggal_hari_ini'");
$jumlah_sakit = mysqli_fetch_assoc($query)['jumlah_sakit'];

// Total kehadiran hari ini
$stmt = $connection->prepare("SELECT COUNT(DISTINCT id_pegawai) as total_hadir 
                              FROM presensi 
                              WHERE tanggal_masuk = ?");
$stmt->bind_param("s", $tanggal_hari_ini);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$total_hadir = $data['total_hadir'];


// Jumlah Alpha
$jumlah_alpha = $total_pegawai - ($total_hadir + $jumlah_sakit);

?>

<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <div class="col-12">
                <div class="row row-cards">
                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-primary text-white avatar">
                                            <!-- Download SVG icon from http://tabler.io/icons/icon/currency-dollar -->
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-users">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                                <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                                <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">
                                            Total Pegawai Aktif
                                        </div>
                                        <div class="text-secondary">
                                            <?= $total_pegawai ?> Pegawai
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-green text-white avatar">
                                            <!-- Download SVG icon from http://tabler.io/icons/icon/shopping-cart -->
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-device-ipad-plus">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M12.5 21h-6.5a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v7" />
                                                <path d="M9 18h3" />
                                                <path d="M16 19h6" />
                                                <path d="M19 16v6" />
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">
                                            Jumlah Hadir
                                        </div>
                                        <div class="text-secondary">
                                            <?= $total_hadir ?> Pegawai
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-x text-white avatar">
                                            <!-- Download SVG icon from http://tabler.io/icons/icon/brand-x -->
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-presentation-off">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M3 4h1m4 0h13" />
                                                <path
                                                    d="M4 4v10a2 2 0 0 0 2 2h10m3.42 -.592c.359 -.362 .58 -.859 .58 -1.408v-10" />
                                                <path d="M12 16v4" />
                                                <path d="M9 20h6" />
                                                <path d="M8 12l2 -2m4 0l2 -2" />
                                                <path d="M3 3l18 18" />
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">
                                            Jumlah Alpa
                                        </div>
                                        <div class="text-secondary">
                                            <?= $jumlah_alpha ?> Pegawai
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="bg-facebook text-white avatar">
                                            <!-- Download SVG icon from http://tabler.io/icons/icon/brand-facebook -->
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-mood-sick">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M12 21a9 9 0 1 1 0 -18a9 9 0 0 1 0 18z" />
                                                <path d="M9 10h-.01" />
                                                <path d="M15 10h-.01" />
                                                <path d="M8 16l1 -1l1.5 1l1.5 -1l1.5 1l1.5 -1l1 1" />
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">
                                            Jumlah Sakit, Izin & Cuti
                                        </div>
                                        <div class="text-secondary">
                                            <?= $jumlah_sakit ?> Pegawai
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('../layout/footer.php'); ?>