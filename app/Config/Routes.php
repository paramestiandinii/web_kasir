<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('produk', 'ProdukCopy::index');
$routes->post('produk/simpan', 'ProdukCopy::simpan_produk');
$routes->get('produk/tampil', 'ProdukCopy::tampil_produk');
$routes->delete('produk/hapus/(:num)', 'ProdukCopy::hapus_produk/$1');
$routes->get('produk/get/(:num)', 'ProdukCopy::get/$1');
$routes->post('produk/update', 'ProdukCopy::update');
// Routes untuk Pelanggan
$routes->get('pelanggan', 'PelangganCopy::index'); // Menampilkan halaman daftar pelanggan
$routes->post('pelanggan/simpan', 'PelangganCopy::simpan_pelanggan'); // Menyimpan data pelanggan baru
$routes->get('pelanggan/tampil', 'PelangganCopy::tampil_pelanggan'); // Menampilkan data pelanggan (biasanya diambil dengan AJAX)
$routes->delete('pelanggan/hapus/(:num)', 'PelangganCopy::hapus_pelanggan/$1'); // Menghapus pelanggan berdasarkan ID
$routes->put('pelanggan/update', 'PelangganCopy::update'); // Mengupdate data pelanggan
$routes->get('pelanggan/(:num)', 'PelangganCopy::get_pelanggan/$1'); // Menampilkan detail pelanggan berdasarkan ID
