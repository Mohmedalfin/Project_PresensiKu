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

$judul = "Ubah Password";
include('../layout/header.php');
require_once('../../config.php');

if (isset($_POST['update'])) {
    $id = $_SESSION['id'];
    $password_input = $_POST['password_baru'];
    $ulangi_password_input = $_POST['ulangi_password_baru'];

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (empty($password_input)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Password wajib diisi";
        }
        if (empty($ulangi_password_input)) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Ulangi password dengan benar";
        }
        if ($password_input !== $ulangi_password_input) {
            $pesan_kesalahan[] = "<i class='fa-solid fa-check'></i> Password tidak cocok ";
        }

        if (!empty($pesan_kesalahan)) {
            $_SESSION['validasi'] = implode("<br>", $pesan_kesalahan);
        } else {
            $password_baru = password_hash($password_input, PASSWORD_DEFAULT);
            $stmt = $connection->prepare("UPDATE users SET password = ? WHERE id_pegawai = ?");
            $stmt->bind_param("ss", $password_baru, $id);
            if ($stmt->execute()) {
                $_SESSION['berhasil'] = 'Password Berhasil diubah';
                echo "<script> window.location.href = '" . base_url('pegawai/home/home.php') . "';</script>";
                exit();
            } else {
                echo "<div class='alert alert-danger'>Gagal update password: " . $stmt->error . "</div>";
            }
        }
    }
}
?>

<!-- Page body -->
<div class="page-body">
    <div class="container-xl">
        <form action="" method="POST">
            <div class="crad col-md-6">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="">Password baru</label>
                        <input type="password" name="password_baru" id="" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="">Ulangi Password</label>
                        <input type="password" name="ulangi_password_baru" id="" class="form-control">
                    </div>

                    <input type="hidden" name="id" value="<?= $_SESSION['id']; ?>" id="">

                    <button type="submit" class="btn btn-primary" name="update">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include('../layout/footer.php'); ?>