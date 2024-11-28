<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir</title>
    <link rel="stylesheet" href="<?= base_url('assets\bootstrap-5.0.2-dist\css\bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets\fontawesome-free-6.6.0-web\css\all.min.css') ?>">
    <script src="<?= base_url('assets\jquery-3.7.1.min.js') ?>"></script>

    <!-- SweetAlert2 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.29/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="row mt-3">
            <div class="col-12">
                <h3 class="text-center">Data Produk</h3>
                <!-- Tombol untuk membuka modal tambah produk -->
                <button type="button" class="btn btn-success float-end" data-bs-toggle="modal"
                    data-bs-target="#modalTambahProduk">
                    <i class="fas fa-cart-plus"></i> Tambah Data
                </button>
            </div>
        </div>

        <!-- Tabel untuk menampilkan data produk -->
        <div class="row">
            <div class="col-12">
                <div class="container mt-5">
                    <table class="table table-bordered" id="produkTable">
                        <thead class="bg-secondary text-white">
                            <tr>
                                <th>No</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Actions</th> <!-- Kolom untuk aksi (Edit/Hapus) -->
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data produk akan dimasukkan melalui AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal untuk tambah atau edit produk -->
        <div class="modal fade" id="modalTambahProduk" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="modalTambahProduk" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h1 class="modal-title fs-5" id="modalTambahProdukLabel">Tambah Produk</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formProduk">
                            <div class="row mb-3">
                                <label for="namaProduk" class="col-sm-4 col-form-label">Nama Produk</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="namaProduk" name="namaProduk">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="hargaProduk" class="col-sm-4 col-form-label">Harga</label>
                                <div class="col-sm-8">
                                    <input type="number" step="0.01" class="form-control" id="hargaProduk">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="stokProduk" class="col-sm-4 col-form-label">Stok</label>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control" id="stokProduk">
                                </div>
                            </div>
                            <!-- Tombol untuk simpan produk -->
                            <button type="button" id="simpanProduk" class="btn btn-primary float-end">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
       
        <script>
            // Fungsi untuk menampilkan produk di tabel
            function tampilProduk() {
                $.ajax({
                    url: '<?= base_url('produk/tampil') ?>', // URL untuk mengambil data produk
                    type: 'GET',
                    dataType: 'json',
                    success: function (hasil) {
                        if (hasil.status === 'success') {
                            var produkTable = $('#produkTable tbody');
                            produkTable.empty(); // Menghapus semua data yang ada di tabel

                            var produk = hasil.produk;
                            var no = 1;

                            produk.forEach(function (item) {
                                // Menambahkan setiap data produk ke dalam tabel
                                var row = '<tr>' +
                                    '<td>' + no + '</td>' +
                                    '<td>' + item.nama_produk + '</td>' +
                                    '<td>' + item.harga + '</td>' +
                                    '<td>' + item.stok + '</td>' +
                                    '<td>' +
                                    '<button class="btn btn-warning btn-sm editProduk" data-id="' + item.produk_id + '" data-nama="' + item.nama_produk + '" data-harga="' + item.harga + '" data-stok="' + item.stok + '"><i class="fa-solid fa-pencil"></i> Edit</button> ' + // Tombol Edit
                                    '<button class="btn btn-danger btn-sm btn-hapusProduk" data-id="' + item.produk_id + '"><i class="fa-solid fa-trash-can"></i> Hapus</button>' + // Tombol Hapus
                                    '</td>' +
                                    '</tr>';
                                produkTable.append(row); // Menambahkan baris baru ke tabel
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

            // Menampilkan produk ketika halaman dimuat
            tampilProduk();
            // untuk tombol simpan produk (menyimpan produk baru atau memperbarui produk yang ada)
            $(document).ready(function () {
                $("#simpanProduk").on("click", function () {
                    var produkId = $(this).data("id"); // Mendapatkan ID produk jika ingin update
                    var formData = {
                        nama_produk: $("#namaProduk").val(),
                        harga: $('#hargaProduk').val(),
                        stok: $('#stokProduk').val()
                    };

                    // Mengecek apakah kita sedang menambah produk baru atau memperbarui produk yang ada
                    if ($(this).data("action") === "update") {
                        // Jika ingin memperbarui produk
                        $.ajax({
                            url: '<?= base_url('produk/update') ?>', // URL untuk update produk
                            type: "POST",
                            data: {
                                produk_id: produkId,
                                nama_produk: formData.nama_produk,
                                harga: formData.harga,
                                stok: formData.stok
                            },
                            dataType: "json",
                            success: function (response) {
                                if (response.status === "success") {
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: response.message,
                                        icon: 'success'
                                    });
                                    $('#modalTambahProduk').modal("hide"); // Menutup modal
                                    tampilProduk(); // Refresh tabel produk
                                } else {
                                    Swal.fire({
                                        title: 'Gagal!',
                                        text: 'Gagal memperbarui data produk.',
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
                        // Jika menambah produk baru
                        $.ajax({
                            url: '<?= base_url('produk/simpan') ?>', // URL untuk menyimpan produk baru
                            type: "POST",
                            data: formData,
                            dataType: 'json',
                            success: function (hasil) {
                                if (hasil.status === 'success') {
                                    $('#modalTambahProduk').modal("hide"); // Menutup modal
                                    $('#formProduk')[0].reset(); // Mereset form input
                                    tampilProduk(); // Refresh tabel produk
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

                // Event handler untuk tombol edit produk
                $(document).on("click", ".editProduk", function () {
                    var produkId = $(this).data("id"); // Mendapatkan ID produk
                    var namaProduk = $(this).data("nama");
                    var hargaProduk = $(this).data("harga");
                    var stokProduk = $(this).data("stok");

                    // Mengisi form dengan data produk yang ingin diubah
                    $("#namaProduk").val(namaProduk);
                    $("#hargaProduk").val(hargaProduk);
                    $("#stokProduk").val(stokProduk);

                    // Set aksi untuk update dan simpan ID produk
                    $("#simpanProduk").data("action", "update").data("id", produkId);
                    $("#modalTambahProduk").modal("show"); // Menampilkan modal
                });

                // Event handler untuk tombol hapus produk
                $('#produkTable').on('click', '.btn-hapusProduk', function () {
                    var produkId = $(this).data('id'); // Mendapatkan ID produk yang ingin dihapus
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: 'Data yang dihapus tidak dapat dipulihkan!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Mengirim request untuk menghapus produk
                            $.ajax({
                                url: '<?= base_url('produk/hapus/') ?>' + produkId,
                                type: 'DELETE',
                                dataType: 'json',
                                success: function (hasil) {
                                    if (hasil.status === 'success') {
                                        tampilProduk(); // Refresh tabel setelah penghapusan
                                    } else {
                                        Swal.fire({
                                            title: 'Gagal!',
                                            text: 'Gagal menghapus produk: ' + hasil.message,
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

    </div>
</body>
</html>