<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"
    integrity="sha512-dQIiHSl2hr3NWKKLycPndtpbh5iaHLo6MwrXm7F0FM5e+kL2U16oE9uIwPHUl6fQBeCthiEuV/rzP3MiAB8Vfw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<style>
    #map {
        height: 338px;
    }
</style>


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

$judul = "Presensi Masuk";
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

if (empty($latitude_pegawai) || empty($longitude_pegawai)) {
    $_SESSION['Gagal'] = "Presensi gagal, Lokasi anda belum aktif";
    header("Location: ../home/home.php");
    exit;
}

if (empty($latitude_kantor) || empty($longitude_kantor)) {
    $_SESSION['Gagal'] = "Presensi gagal, koordinat kantor belum disetting";
    header("Location: ../home/home.php");
    exit;
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
                <!-- Kolom Google Maps -->
                <div class="col-md-6 ">
                    <div class="card">
                        <div class="card-body">
                            <div id="map"></div>
                        </div>
                    </div>
                </div>
                <!-- Kolom Kamera dan Informasi -->
                <div class="col-md-6">
                    <div class="card text-center rounded">
                        <div class="card-body  mt-2" style="margin: auto;">
                            <input type="hidden" id="id" value="<?= $_SESSION['id'] ?>">
                            <input type="hidden" id="tanggal_masuk" value="<?= $tanggal_masuk ?>">
                            <input type="hidden" id="jam_masuk" value="<?= $jam_masuk ?>">
                            <div id="my_camera">
                            </div>
                            <div id="my_result"></div>
                            <div class="mt-3">
                                <?= date('d F Y', strtotime($tanggal_masuk)) . ' - ' . $jam_masuk ?>
                            </div>
                            <button class="btn btn-primary mt-3" id="ambil-foto">Masuk</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script language="JavaScript">
        Webcam.set({
            width: 320,
            height: 240,
            dest_width: 320,
            dest_height: 240,
            image_format: 'jpeg',
            jpeg_quality: 90,
            force_flash: false
        });
        Webcam.attach('#my_camera');

        document.getElementById('ambil-foto').addEventListener('click', function () {

            let id = document.getElementById('id').value;
            let tanggal_masuk = document.getElementById('tanggal_masuk').value;
            let jam_masuk = document.getElementById('jam_masuk').value;

            Webcam.snap(function (data_uri) {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    document.getElementById('my_result').innerHTML = '<img src="' + data_uri + '"/>';
                    if (xhttp.readyState == 4 && xhttp.status == 200) {
                        window.location.href = '../home/home.php';
                    }
                };
                xhttp.open("POST", "presensi_masuk_aksi.php", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send(
                    'photo=' + encodeURIComponent(data_uri) +
                    '&id=' + encodeURIComponent(id) +
                    '&tanggal_masuk=' + encodeURIComponent(tanggal_masuk) +
                    '&jam_masuk=' + encodeURIComponent(jam_masuk)
                );
            });
        });
        // leaflet js
        let latitude_ktr = <?= $latitude_kantor ?>;
        let longitude_ktr = <?= $longitude_kantor ?>;
        let latitude_peg = <?= $latitude_pegawai ?>;
        let longitude_peg = <?= $longitude_pegawai ?>;
        let map = L.map('map').setView([latitude_ktr, longitude_ktr], 13);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        var marker = L.marker([latitude_ktr, longitude_ktr]).addTo(map);

        var circle = L.circle([latitude_peg, longitude_peg], {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.5,
            radius: 500
        }).addTo(map).bindPopup("Lokasi anda saat ini");
    </script>
    <!-- <a href="javascript:void(take_snapshot())">Take Snapshot</a> -->
<?php } ?>

<?php include('../layout/footer.php'); ?>