<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>kasir</title>
    <link rel="stylesheet" href="<?= base_url('assets\bootstrap-5.0.2-dist\css\bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets\fontawesome-free-6.6.0-web\css\all.min.css') ?>">
    <script src="<?= base_url('assets\jquery-3.7.1.min.js') ?>"></script>
</head>
<body>
<div class="container">
        <div class="row mt-3">
            <div class="col-12">
                <h3 class="text-center">Pelanggan</h3>
                <!-- Tombol untuk membuka modal tambah pelanggan -->
                <button type="button" class="btn btn-success float-end" data-bs-toggle="modal"
                    data-bs-target="#modalTambahProduk">
                    <i class="fas fa-user"></i> Data Pelanggan
                </button>
            </div>
        </div>

         <!-- Tabel untuk menampilkan data pelanggan -->
         <div class="row">
            <div class="col-12">
                <div class="container mt-5">
                    <table class="table table-bordered" id="pelangganTable">
                        <thead class="bg-secondary text-white">
                            <tr>
                                <th>No</th>
                                <th>Nama Pelanggan</th>
                                <th>Alamat</th>
                                <th>Telepon</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data pelanggan akan dimasukkan melalui AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal untuk tambah atau edit pelanggan -->
        <div class="modal fade" id="modalTambahProduk" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="modalTambahProduk" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h1 class="modal-title fs-5" id="modalTambahProdukLabel">Data Pelanggan</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formProduk">
                            <div class="row mb-3">
                                <label for="namaPelanggan" class="col-sm-4 col-form-label">Nama Pelanggan</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="namaPelanggan" name="namaPelanggan">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="alamat" class="col-sm-4 col-form-label">Alamat</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="alamat" name="alamat">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="telepon" class="col-sm-4 col-form-label">Telepon</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="telepon" name="telepon">
                                </div>
                            </div>
                            <!-- Tombol untuk simpan pelanggan -->
                            <button type="button" id="simpanProduk" class="btn btn-primary float-end">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Fungsi untuk menampilkan pelanggan di tabel
            function tampilPelanggan() {
                $.ajax({
                    url: '<?= base_url('pelanggan/tampil') ?>', // URL untuk mengambil data pelanggan
                    type: 'GET',
                    dataType: 'json',
                    success: function (hasil) {
                        if (hasil.status === 'success') {
                            var pelangganTable = $('#pelangganTable tbody');
                            pelangganTable.empty(); // Menghapus semua data yang ada di tabel

                            var pelanggan = hasil.pelanggan;
                            var no = 1;

                            pelanggan.forEach(function (item) {
                                // Menambahkan setiap data pelanggan ke dalam tabel
                                var row = '<tr>' +
                                    '<td>' + no + '</td>' +
                                    '<td>' + item.nama_pelanggan + '</td>' +
                                    '<td>' + item.alamat + '</td>' +
                                    '<td>' + item.telepon + '</td>' +
                                    '<td>' +
                                    '<button class="btn btn-warning btn-sm editProduk" data-id="' + item.pelanggan_id + '" data-nama="' + item.nama_pelanggan + '" data-harga="' + item.alamat + '" data-alamat="' + item.telepon + '"><i class="fa-solid fa-pencil"></i> Edit</button> ' + // Tombol Edit
                                    '<button class="btn btn-danger btn-sm btn-hapusProduk" data-id="' + item.pelanggan_id + '"><i class="fa-solid fa-trash-can"></i> Hapus</button>' + // Tombol Hapus
                                    '</td>' +
                                    '</tr>';
                                pelangganTable.append(row); // Menambahkan baris baru ke tabel
                                no++; // Menambah nomor urut
                            });
                        } else {
                            Swal.fire({  // Jika gagal mengambil data
                                title: "Gagal!",
                                text: "Terjadi kesalahan saat menyimpan data",
                                icon: "error"
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        Swal.fire({    // Menampilkan pesan kesalahan
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan: ' + error,
                            icon: 'error',
                        });
                    }
                });
            }

            // Menampilkan pelanggan ketika halaman dimuat
            tampilPelanggan();
            // untuk tombol simpan pelanggan (menyimpan pelanggan baru atau memperbarui pelanggan yang ada)
            $(document).ready(function () {
                $("#simpanProduk").on("click", function () {
                    var pelangganId = $(this).data("id"); // Mendapatkan ID pelanggan jika ingin update
                    var formData = {
                        nama_pelanggan: $("#namaPelanggan").val(),
                        alamat: $('#alamat').val(),
                        telepon: $('#telepon').val()
                    };

                    // Mengecek apakah kita sedang menambah pelanggan baru atau memperbarui pelanggan yang ada
                    if ($(this).data("action") === "update") {
                        // Jika ingin memperbarui pelanggan
                        $.ajax({
                            url: '<?= base_url('pelanggan/update') ?>', // URL untuk update pelanggan
                            type: "POST",
                            data: {
                                pelanggan_id: pelangganId,
                                nama_pelanggan: formData.nama_pelanggan,
                                alamat: formData.alamat,
                                telepon: formData.telepon
                            },
                            dataType: "json",
                            success: function (response) {
                                if (response.status === "success") {
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: response.message,
                                        icon: 'success'
                                    });
                                    $('#modalTambahPelanggan').modal("hide"); // Menutup modal
                                    tampilPelanggan(); // Refresh tabel pelanggan
                                } else {
                                    Swal.fire({
                                        title: 'Gagal!',
                                        text: 'Gagal memperbarui data pelanggan.',
                                        icon: 'error'
                                    });
                                }
                            },
                            error: function (xhr, status, error) {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: 'Terjadi kesalahan: ' + error,
                                    icon: 'error'
                                });
                            }
                        });
                    } else {
                        // Jika menambah pelanggan baru
                        $.ajax({
                            url: '<?= base_url('pelanggan/simpan') ?>', // URL untuk menyimpan pelanggan baru
                            type: "POST",
                            data: formData,
                            dataType: 'json',
                            success: function (hasil) {
                                if (hasil.status === 'success') {
                                    $('#modalTambahPelanggan').modal("hide"); // Menutup modal
                                    $('#formProduk')[0].reset(); // Mereset form input
                                    tampilPelanggan(); // Refresh tabel pelanggan
                                } else {
                                    Swal.fire({
                                        title: 'Gagal!',
                                        text: 'Gagal menyimpan data: ' + JSON.stringify(hasil.errors),
                                        icon: 'error'
                                    });
                                }
                            },
                            error: function (xhr, status, error) {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: 'Terjadi kesalahan: ' + error,
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });

                // Event handler untuk tombol edit pelanggan
                $(document).on("click", ".editPelanggan", function () {
                    var pelangganId = $(this).data("id"); // Mendapatkan ID pelanggan
                    var namaPelanggan = $(this).data("nama_pelanggan");
                    var alamatPelanggan = $(this).data("alamat");
                    var teleponPelanggan = $(this).data("telepon");

                    // Mengisi form dengan data pelanggan yang ingin diubah
                    $("#namaPelanggan").val(namaPelanggan);
                    $("#alamatPelanggan").val(alamatPelanggan);
                    $("#teleponPelanggan").val(teleponPelanggan);

                    // Set aksi untuk update dan simpan ID pelanggan
                    $("#simpanPelanggan").data("action", "update").data("id", pelangganId);
                    $("#modalTambahPelanggan").modal("show"); // Menampilkan modal
                });

                // Event handler untuk tombol hapus pelanggan
                $('#pelangganTable').on('click', '.btn-hapusPelanggan', function () {
                    var pelangganId = $(this).data('id'); // Mendapatkan ID pelanggan yang ingin dihapus
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: 'Data yang dihapus tidak dapat dipulihkan!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Mengirim request untuk menghapus pelanggan
                            $.ajax({
                                url: '<?= base_url('pelanggan/hapus/') ?>' + pelangganId,
                                type: 'DELETE',
                                dataType: 'json',
                                success: function (hasil) {
                                    if (hasil.status === 'success') {
                                        tampilPelanggan(); // Refresh tabel setelah penghapusan
                                    } else {
                                        Swal.fire({
                                            title: 'Gagal!',
                                            text: 'Gagal menghapus pelanggan: ' + hasil.message,
                                            icon: 'error'
                                        });
                                    }
                                },
                                error: function (xhr, status, error) {
                                    Swal.fire({
                                        title: 'Gagal!',
                                        text: 'Terjadi kesalahan: ' + error,
                                        icon: 'error'
                                    });
                                }
                            });
                        }
                    });
                });
            });
        </script>
        
        <script src="<?= base_url('assets\bootstrap-5.0.2-dist\js\bootstrap.min.js') ?>"></script>
        <script src="<?= base_url('assets\jquery-3.7.1.min.js') ?>"></script>
        <script src="<?= base_url('assets\fontawesome-free-6.6.0-web\js\all.min.js') ?>"></script>
        <!-- SweetAlert2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.29/dist/sweetalert2.all.min.js"></script>
</body>
</html>