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

$judul = "Update Data Pegawai";
include('../layout/header.php');
require_once('../../config.php');

if (isset($_POST['edit'])) {
    $pesan_kesalahan = [];
    $id = $_POST['id'];
    $name = htmlspecialchars($_POST['name']);
    $jenis_kelamin = htmlspecialchars($_POST['jenis_kelamin']);
    $alamat = htmlspecialchars($_POST['alamat']);
    $no_handphone = htmlspecialchars($_POST['no_handphone']);
    $jabatan = htmlspecialchars($_POST['jabatan']);
    $username = htmlspecialchars($_POST['username']);
    $role = htmlspecialchars($_POST['role']);
    $status = htmlspecialchars($_POST['status']);
    $lokasi_presensi = htmlspecialchars($_POST['lokasi_presensi']);

    // Update password
    if (!empty($_POST['password'])) {
        if ($_POST['password'] !== $_POST['password_confirm']) {
            $pesan_kesalahan[] = "Konfirmasi password tidak cocok.";
        } else {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }
    } else {
        $password = $_POST['password_lama'];
    }


    // Update Foto
    if ($_FILES['foto_baru']['error'] === 4) {
        $nama_file = $_POST['foto_lama'];
    } else {
        $file = $_FILES['foto_baru'];
        $ekstensi_diperbolehkan = ['jpg', 'jpeg', 'png'];
        $maks_ukuran = 2 * 1024 * 1024; // 2MB
        $nama_file = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "_", basename($file['name']));
        $file_tmp = $file['tmp_name'];
        $ukuran_file = $file['size'];
        $ekstensi = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));

        if (!in_array($ekstensi, $ekstensi_diperbolehkan)) {
            $pesan_kesalahan[] = "Ekstensi file harus jpg, jpeg, atau png.";
        } elseif ($ukuran_file > $maks_ukuran) {
            $pesan_kesalahan[] = "Ukuran file maksimal 2MB.";
        } else {
            move_uploaded_file($file_tmp, '../../presensi/img/foto_pegawai/' . $nama_file);
        }
    }

    // Validasi input wajib
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
    if (empty($_POST['password']) && empty($_POST['password_lama'])) {
        $pesan_kesalahan[] = "Password tidak boleh kosong.";
    }
    if (empty($role))
        $pesan_kesalahan[] = "Role tidak boleh kosong.";
    if (empty($lokasi_presensi))
        $pesan_kesalahan[] = "Lokasi presensi tidak boleh kosong.";

    if (!empty($pesan_kesalahan)) {
        // Tampilkan pesan kesalahan (contoh pakai alert Bootstrap)
        foreach ($pesan_kesalahan as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    } else {
        // Jalankan query jika tidak ada error
        $query = "UPDATE `pegawai` SET `name`='$name',`jenis_kelamin`='$jenis_kelamin',`alamat`='$alamat',`no_handphone`='$no_handphone',`jabatan`='$jabatan',`lokasi_presensi`='$lokasi_presensi',`foto`='$nama_file' WHERE  id = '$id'";
        $result1 = mysqli_query($connection, $query);

        $user = "UPDATE `users` SET `username`='$username',`password`='$password',`status`='$status',`role`='$role' WHERE id = '$id'";
        $result2 = mysqli_query($connection, $user);

        if ($result1 && $result2) {
            $_SESSION['berhasil'] = 'Data pegawai dan user berhasil update';
        } else {
            $_SESSION['validasi'] = 'Gagal mengupdate data: ' . mysqli_error($connection);
        }

        header("Location: " . base_url('admin/data_pegawai/pegawai.php'));
        exit();
    }

}

$id = isset($_GET['id']) ? $_GET['id'] : $_POST['id'];
$result = mysqli_query($connection, "SELECT users.id_pegawai, users.username, users.password, users.status, users.role, pegawai.* FROM users JOIN pegawai ON users.id_pegawai = pegawai.id WHERE pegawai.id = '$id'");

while ($pegawai = mysqli_fetch_array($result)) {
    $name = $pegawai['name'];
    $jenis_kelamin = $pegawai['jenis_kelamin'];
    $alamat = $pegawai['alamat'];
    $no_handphone = $pegawai['no_handphone'];
    $jabatan = $pegawai['jabatan'];
    $lokasi_presensi = $pegawai['lokasi_presensi'];
    $username = $pegawai['username'];
    $password = $pegawai['password'];
    $status = $pegawai['status'];
    $role = $pegawai['role'];
    $foto = $pegawai['foto'];
}

?>

<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <form action="<?= base_url('admin/data_pegawai/edit.php') ?>" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">

                            <div class="md-3 mt-2">
                                <label for="" class="mb-1">Nama</label>
                                <input type="text" class="form-control" name="name" id="name" value="<?= $name ?>"
                                    required>
                            </div>
                            <div class="md-3 mt-2">
                                <label for="" class="mb-1">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-control">
                                    <option value="">--- Jenis Kelamin ---</option>
                                    <option value="Laki-Laki"
                                        <?= trim($jenis_kelamin) == 'Laki-Laki' ? 'selected' : '' ?>>
                                        Laki-Laki</option>
                                    <option value="Perempuan"
                                        <?= trim($jenis_kelamin) == 'Perempuan' ? 'selected' : '' ?>>
                                        Perempuan</option>
                                </select>

                            </div>
                            <div class="md-3 mt-2">
                                <label for="" class="mb-1">Alamat</label>
                                <input type="text" class="form-control" name="alamat" id="alamat" value="<?= $alamat ?>"
                                    required>
                            </div>
                            <div class="md-3 mt-2">
                                <label for="" class="mb-1">No. Handphone</label>
                                <input type="text" class="form-control" name="no_handphone" id="no_handphone"
                                    value="<?= $no_handphone ?>" required>
                            </div>

                            <div class="md-3 mt-2">
                                <label for="" class="mb-1">Jabatan</label>
                                <select name="jabatan" class="form-control">
                                    <option value="">--- Pilih Jabatan---</option>
                                    <?php
                                    $ambil_jabatan = mysqli_query($connection, "SELECT * FROM jabatan ORDER BY jabatan ASC");
                                    while ($row_jabatan = mysqli_fetch_assoc($ambil_jabatan)) {
                                        $nama_jabatan = $row_jabatan['jabatan'];
                                        $selected = ($nama_jabatan == trim($jabatan)) ? 'selected' : '';
                                        echo '<option value="' . $nama_jabatan . '" ' . $selected . '>' . $nama_jabatan . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class=" md-3 mt-2">
                                <label for="" class="mb-1">Lokasi Presensi</label>
                                <select name="lokasi_presensi" class="form-control">
                                    <option value="">--- Pilih Lokasi Presensi ---</option>
                                    <?php
                                    $ambil_lok_presensi = mysqli_query($connection, "SELECT * FROM lokasi_presensi ORDER BY nama_lokasi ASC");
                                    while ($lokasi = mysqli_fetch_assoc($ambil_lok_presensi)) {
                                        $nama_lokasi = $lokasi['nama_lokasi'];
                                        if ($lokasi_presensi == $nama_lokasi) {
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
                                <input type="text" class="form-control" name="username" id="username"
                                    value="<?= $username ?>" required>
                            </div>
                            <div class="md-3 mt-2">
                                <label for="" class="mb-1">Password</label>
                                <input type="hidden" value="<?= $password ?>" name="password_lama">
                                <input type="password" class="form-control" name="password">
                            </div>
                            <div class="md-3 mt-2">
                                <label for="" class="mb-1">Confirm Password</label>
                                <input type="password" class="form-control" name="password_confirm"
                                    id="password_confirm">
                            </div>

                            <div class="md-3 mt-2">
                                <label for="" class="mb-1">Status</label>
                                <select name="status" class="form-control">
                                    <option value="">--- Status pegawai---</option>
                                    <option <?php if ($status == 'Aktif') {
                                        echo 'selected';
                                    } ?> value=" Aktif">
                                        Aktif
                                    </option>
                                    <option <?php if ($status == 'Non-Aktif') {
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
                                    <option <?php if ($role == 'admin') {
                                        echo 'selected';
                                    } ?> value="admin">
                                        Admin
                                    </option>
                                    <option <?php if ($role == 'pegawai') {
                                        echo 'selected';
                                    } ?> value="pegawai">
                                        Pegawai
                                    </option>
                                </select>
                            </div>
                            <div class="md-3 mt-2">
                                <label for="" class="mb-1">Foto</label>
                                <input type="hidden" value="<?= $foto ?>" name="foto_lama">
                                <input type="file" class="form-control" name="foto_baru" id="foto"
                                    placeholder="Masukkan foto">
                            </div>
                            <input type="text" value="<?= $id ?>" name="id">
                            <button class="btn btn-primary mt-3" name="edit" type="submit">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include('../layout/footer.php'); ?>