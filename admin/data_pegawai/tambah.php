<?php
session_start();
ob_start();

if (!isset($_SESSION["login"])) {
    header("Location: ../../auth/login.php?pesan=belum_login");
    exit;
} elseif ($_SESSION["role"] != 'admin') {
    header("Location: ../../auth/login.php?pesan=tolak_akses");
    exit;
}

$judul = "Tambah Pegawai";
include('../layout/header.php');
require_once('../../config.php');

if (isset($_POST['submit'])) {
    $ambil_nip = mysqli_query($connection, "SELECT nip FROM pegawai ORDER BY id DESC LIMIT 1");
    if (mysqli_num_rows($ambil_nip) > 0) {
        $row = mysqli_fetch_assoc($ambil_nip);
        $nip_db = $row['nip'];
        $nip_db = explode('-', $nip_db);
        $no_baru = (int) $nip_db[1] + 1;
        $nip_baru = 'PEG-' . str_pad($no_baru, 4, '0', STR_PAD_LEFT);
    } else {
        $nip_baru = 'PEG-001';
    }
    $nip = $nip_baru;
    $name = htmlspecialchars($_POST['name']);
    $jenis_kelamin = htmlspecialchars($_POST['jenis_kelamin']);
    $alamat = htmlspecialchars($_POST['alamat']);
    $no_handphone = htmlspecialchars($_POST['no_handphone']);
    $jabatan = htmlspecialchars($_POST['jabatan']);
    $username = htmlspecialchars($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = htmlspecialchars($_POST['role']);
    $status = htmlspecialchars($_POST['status']);
    $lokasi_presensi = htmlspecialchars($_POST['lokasi_presensi']);



    $pesan_kesalahan = [];

    // Validasi input wajib
    if (empty($nip))
        $pesan_kesalahan[] = "NIP tidak boleh kosong.";
    if (empty($name))
        $pesan_kesalahan[] = "Nama tidak boleh kosong.";
    if (empty($jenis_kelamin))
        $pesan_kesalahan[] = "Jenis kelamin tidak boleh kosong.";
    if (empty($alamat))
        $pesan_kesalahan[] = "Alamat tidak boleh kosong.";
    if (empty($no_handphone))
        $pesan_kesalahan[] = "Nomor handphone tidak boleh kosong.";
    if (empty($jabatan))
        $pesan_kesalahan[] = "Jabatan tidak boleh kosong.";
    if (empty($status))
        $pesan_kesalahan[] = "Status tidak boleh kosong.";
    if (empty($username))
        $pesan_kesalahan[] = "Username tidak boleh kosong.";
    if (empty($_POST['password']))
        $pesan_kesalahan[] = "Password tidak boleh kosong.";
    if (empty($role))
        $pesan_kesalahan[] = "Role tidak boleh kosong.";
    if (empty($lokasi_presensi))
        $pesan_kesalahan[] = "Lokasi presensi tidak boleh kosong.";

    // Handle upload foto
    $nama_file = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['foto'];
        $nama_file = time() . '_' . basename($file['name']); // rename file agar unik
        $file_tmp = $file['tmp_name'];
        $ukuran_file = $file['size'];
        $ekstensi = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
        $ekstensi_diperbolehkan = ['jpg', 'jpeg', 'png'];
        $maks_ukuran = 10 * 1024 * 1024; // 2MB

        if (!in_array($ekstensi, $ekstensi_diperbolehkan)) {
            $pesan_kesalahan[] = "Ekstensi file harus jpg, jpeg, atau png.";
        } elseif ($ukuran_file > $maks_ukuran) {
            $pesan_kesalahan[] = "Ukuran file maksimal 2MB.";
        } else {
            move_uploaded_file($file_tmp, '../../presensi/img/foto_pegawai/' . $nama_file);
        }
    }

    if (!empty($pesan_kesalahan)) {
        foreach ($pesan_kesalahan as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    } else {
        $query = "INSERT INTO pegawai 
        (nip, name, jenis_kelamin, alamat, no_handphone, jabatan, lokasi_presensi, foto) 
        VALUES 
        ('$nip', '$name', '$jenis_kelamin', '$alamat', '$no_handphone', '$jabatan', '$lokasi_presensi', '$nama_file')";

        $result1 = mysqli_query($connection, $query);

        $id_pegawai = mysqli_insert_id($connection); // ambil id pegawai yang baru saja ditambahkan
        $user = "INSERT INTO users 
        (id_pegawai, username, password, status, role) 
        VALUES 
        ('$id_pegawai', '$username', '$password', '$status', '$role')";


        $result2 = mysqli_query($connection, $user);  // untuk tabel users

        if ($result1 && $result2) {
            $_SESSION['berhasil'] = 'Data pegawai dan user berhasil ditambahkan';
        } else {
            $_SESSION['validasi'] = 'Gagal menambahkan data: ' . mysqli_error($connection);
        }

        header("Location: " . base_url('admin/data_pegawai/pegawai.php'));
        exit();
    }
}
?>

<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <form action="<?= base_url('admin/data_pegawai/tambah.php') ?>" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">

                            <!-- <div class="md-3">
                                <label for="" class="mb-1">NIP</label>
                                <input type="text" class="form-control" name="nip" id="nip" value="<?= $nip_baru ?>"
                                    placeholder="Masukka NIP" required>
                            </div> -->
                            <div class="md-3 mt-2">
                                <label for="" class="mb-1">Nama</label>
                                <input type="text" class="form-control" name="name" id="name" value="<?php if (isset($_POST['name']))
                                    echo $_POST['name'] ?>" placeholder="Masukkan name" required>
                            </div>
                            <div class="md-3 mt-2">
                                <label for="" class="mb-1">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-control">
                                    <option value="">--- Jenis Kelamin ---</option>
                                    <option <?php if (isset($_POST['jenis_kelamin']) && $_POST['jenis_kelamin'] == 'Laki-Laki') {
                                    echo 'selected';
                                } ?> value="Laki-Laki">
                                        Laki-Laki
                                    </option>
                                    <option <?php if (isset($_POST['jenis_kelamin']) && $_POST['jenis_kelamin'] == 'Perempuan') {
                                        echo 'selected';
                                    } ?> value="Perempuan">
                                        Perempuan
                                    </option>
                                </select>
                            </div>
                            <div class="md-3 mt-2">
                                <label for="" class="mb-1">Alamat</label>
                                <input type="text" class="form-control" name="alamat" id="alamat" value="<?php if (isset($_POST['alamat']))
                                    echo $_POST['alamat'] ?>" placeholder="Masukkan alamat" required>
                            </div>
                            <div class="md-3 mt-2">
                                <label for="" class="mb-1">No. Handphone</label>
                                <input type="text" class="form-control" name="no_handphone" id="no_handphone" value="<?php if (isset($_POST['no_handphone']))
                                    echo $_POST['no_handphone'] ?>" placeholder="Masukkan no handphone" required>
                            </div>

                            <div class="md-3 mt-2">
                                <label for="" class="mb-1">Jabatan</label>
                                <select name="jabatan" class="form-control">
                                    <option value="">--- Pilih Jabatan---</option>
                                    <?php
                                $ambil_jabatan = mysqli_query($connection, "SELECT * FROM jabatan ORDER BY jabatan ASC");
                                while ($jabatan = mysqli_fetch_assoc($ambil_jabatan)) {
                                    $nama_jabatan = $jabatan['jabatan'];
                                    if (isset($_POST['jabatan']) && $_POST['jabatan'] == $nama_jabatan) {
                                        echo '<option selected value="' . $nama_jabatan . '">' . $nama_jabatan . '</option>';
                                    } else {
                                        echo '<option value="' . $nama_jabatan . '">' . $nama_jabatan . '</option>';
                                    }
                                }
                                ?>
                                </select>
                            </div>
                            <div class="md-3 mt-2">
                                <label for="" class="mb-1">Lokasi Presensi</label>
                                <select name="lokasi_presensi" class="form-control">
                                    <option value="">--- Pilih Lokasi Presensi ---</option>
                                    <?php
                                    $ambil_lok_presensi = mysqli_query($connection, "SELECT * FROM lokasi_presensi ORDER BY nama_lokasi ASC");
                                    while ($lokasi = mysqli_fetch_assoc($ambil_lok_presensi)) {
                                        $nama_lokasi = $lokasi['nama_lokasi'];
                                        if (isset($_POST['lokasi_presensi']) && $_POST['lokasi_presensi'] == $nama_lokasi) {
                                            echo '<option selected value="' . $nama_lokasi . '">' . $nama_lokasi . '</option>';
                                        } else {
                                            echo '<option value="' . $nama_lokasi . '">' . $nama_lokasi . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="md-3 mt-2">
                                <label for="" class="mb-1">Username</label>
                                <input type="text" class="form-control" name="username" id="username" value="<?php if (isset($_POST['username']))
                                    echo $_POST['username'] ?>" placeholder="Masukkan Username" required>
                            </div>
                            <div class="md-3 mt-2">
                                <label for="" class="mb-1">Password</label>
                                <input type="password" class="form-control" name="password" id="password"
                                    placeholder=" Masukkan password" required>
                            </div>
                            <div class="md-3 mt-2">
                                <label for="" class="mb-1">Confirm Password</label>
                                <input type="password" class="form-control" name="password" id="password"
                                    placeholder=" Ulangi password" required>
                            </div>
                            <div class="md-3 mt-2">
                                <label for="" class="mb-1">Status</label>
                                <select name="status" class="form-control">
                                    <option value="">--- Status pegawai---</option>
                                    <option <?php if (isset($_POST['status']) && $_POST['status'] == 'Aktif') {
                                    echo 'selected';
                                } ?> value="Aktif">
                                        Aktif
                                    </option>
                                    <option <?php if (isset($_POST['status']) && $_POST['status'] == 'Non-Aktif') {
                                        echo 'selected';
                                    } ?> value="Non-Aktif">
                                        Non-Aktif
                                    </option>
                                </select>
                            </div>
                            <div class="md-3 mt-2">
                                <label for="" class="mb-1">Role</label>
                                <select name="role" class="form-control">
                                    <option value="">--- Role---</option>
                                    <option <?php if (isset($_POST['role']) && $_POST['role'] == 'admin') {
                                        echo 'selected';
                                    } ?> value="admin">
                                        Admin
                                    </option>
                                    <option <?php if (isset($_POST['role']) && $_POST['role'] == 'pegawai') {
                                        echo 'selected';
                                    } ?> value="pegawai">
                                        Pegawai
                                    </option>
                                </select>
                            </div>
                            <div class="md-3 mt-2">
                                <label for="" class="mb-1">Foto</label>
                                <input type="file" class="form-control" name="foto" id="foto"
                                    placeholder=" Masukkan foto" required>
                            </div>
                            <button class="btn btn-primary mt-3" name="submit" type="submit">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include('../layout/footer.php'); ?>