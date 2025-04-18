<footer class="footer footer-transparent d-print-none">
    <div class="container-xl">
        <div class="row text-center align-items-center flex-row-reverse">
            <div class="col-lg-auto ms-lg-auto">
                <ul class="list-inline list-inline-dots mb-0">
                    <li class="list-inline-item"><a href="https://tabler.io/docs" target="_blank" class="link-secondary"
                            rel="noopener">Documentation</a></li>
                    <li class="list-inline-item"><a href="./license.html" class="link-secondary">License</a>
                    </li>
                    <li class="list-inline-item"><a href="https://github.com/tabler/tabler" target="_blank"
                            class="link-secondary" rel="noopener">Source code</a></li>
                    <li class="list-inline-item">
                        <a href="https://github.com/sponsors/codecalm" target="_blank" class="link-secondary"
                            rel="noopener">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/heart -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon text-pink icon-inline icon-4">
                                <path
                                    d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572" />
                            </svg>
                            Sponsor
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                <ul class="list-inline list-inline-dots mb-0">
                    <li class="list-inline-item">
                        Copyright &copy; 2025
                        <a href="." class="link-secondary">Tabler</a>.
                        All rights reserved.
                    </li>
                    <li class="list-inline-item">
                        <a href="./changelog.html" class="link-secondary" rel="noopener">
                            v1.0.0
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>
</div>
</div>

<!-- CDN JS Switch ALert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Libs JS -->
<script src="<?= base_url('presensi/libs/apexcharts/dist/apexcharts.min.js?1738096685') ?>" defer></script>
<script src="<?= base_url('presensi/libs/jsvectormap/dist/jsvectormap.min.js?1738096685') ?>" defer></script>
<script src="<?= base_url('presensi/libs/jsvectormap/dist/maps/world.js?1738096685') ?>" defer></script>
<script src="<?= base_url('presensi/libs/jsvectormap/dist/maps/world-merc.js?1738096685') ?>" defer></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<!-- Tabler Core -->
<script src="<?= base_url('presensi/js/tabler.min.js?1738096685') ?>" defer></script>
<script src="<?= base_url('presensi/js/demo.min.js?1738096685') ?>" defer></script>

<!-- alert validasi -->
<?php if (isset($_SESSION['validasi'])): ?>

<script>
const Toast = Swal.mixin({
    toast: true,
    position: "top",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    }
});
Toast.fire({
    icon: "error",
    title: "<?= $_SESSION['validasi'] ?>"
});
</script>

<?php unset($_SESSION['validasi']); ?>

<?php endif; ?>

<!-- alert berhasil -->
<?php if (isset($_SESSION['berhasil'])): ?>

<script>
const berhasil = Swal.mixin({
    toast: true,
    position: "top",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    }
});
berhasil.fire({
    icon: "success",
    title: "<?= $_SESSION['berhasil'] ?>"
});
</script>

<?php unset($_SESSION['berhasil']); ?>

<?php endif; ?>

<!-- alert konfirmasi hapus -->

<script>
$('.tombol-hapus').on('click', function() {
    var getlink = $(this).attr('href');
    Swal.fire({
        title: "Yakin hapus?",
        text: "Data yang sudah dihapus tidak bisa dikembalikan",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya, dihapus!",
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = getlink
        }
    });
    return false;
});
</script>

</body>

</html>