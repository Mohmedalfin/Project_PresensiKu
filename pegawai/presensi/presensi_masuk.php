<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"
    integrity="sha512-dQIiHSl2hr3NWKKLycPndtpbh5iaHLo6MwrXm7F0FM5e+kL2U16oE9uIwPHUl6fQBeCthiEuV/rzP3MiAB8Vfw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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


include('../layout/header.php');
include_once('../../config.php');

if (isset($_POST['tombol_masuk'])) {
    $latitude_pegawai = $_POST['latitude_pegawai'];
    $longitude_pegawai = $_POST['longitude_pegawai'];
    $latitude_kantor = $_POST['latitude_kantor'];
    $longitude_kantor = $_POST['longitude_kantor'];
    $radius = $_POST['radius'];
    $zona_waktu = $_POST['zona_waktu'];
    $tanggal_masuk = $_POST['tanggal_masuk'];
    $jam_masuk = $_POST['jam_masuk'];
}

$perbedaaan_kordinat = $longitude_pegawai - $longitude_kantor;
$jarak = sin(deg2rad($latitude_pegawai)) * sin(deg2rad($latitude_kantor)) + cos(deg2rad($latitude_pegawai)) * cos(deg2rad($latitude_kantor)) * cos(deg2rad($perbedaaan_kordinat));
$jarak = acos($jarak);
$jarak = rad2deg($jarak);
$mil = $jarak * 60 * 1.1515;
$jarak_km = $mil * 1.609344;
$jarak_meter = $jarak_km * 1000;


?>

<?php if ($jarak_meter > $radius) { ?>
    <?=
        $_SESSION['Gagal'] = "Anda berada di luar area kantor";
    header("Location: ../home/home.php");
    exit;
?>
<?php } else { ?>
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d19623.044937042283!2d<?= $longitude_pegawai ?>!3d<?= $latitude_pegawai ?>!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zN8KwNDQnMzYuNyJTIDExMMKwMjEnNDAuNyJF!5e1!3m2!1sid!2sid!4v1748425572998!5m2!1sid!2sid"
                                width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 rounded">
                    <div class="card text-center">
                        <div class="card-body" style="margin: auto;">
                            <div id="my_camera" style="width:320px; height:240px;"></div>
                            <div id="my_result"></div>
                            <div class="mt-3"> <?= $tanggal_masuk . '-' . $jam_masuk ?></div>
                            <button class="btn btn-primary mt-3" id="ambil-foto">Masuk</button>
                        </div>
                    </div>
                </div>

                <script language="JavaScript">
                    Webcam.attach('#my_camera');

                    function take_snapshot() {
                        Webcam.snap(function (data_uri) {
                            document.getElementById('my_result').innerHTML = '<img src="' + data_uri +
                                '"/>';
                        });
                    }
                </script>
                <a href="javascript:void(take_snapshot())">Take Snapshot</a>

            </div>
        </div>
    </div>
<?php } ?>

<?php include('../layout/footer.php'); ?>