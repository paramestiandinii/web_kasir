<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ProdukModel;

class ProdukCopy extends BaseController
{
    protected $produkmodel;

    public function __construct()
    {
        // Inisialisasi ProdukModel
        $this->produkmodel = new ProdukModel();
    }

    public function index()
    {
        // Menampilkan view beranda
        return view('beranda'); 
    }

    public function simpan_produk()
    {
        // Validasi input dari AJAX
        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama_produk' => 'required',
            'harga' => 'required|decimal',
            'stok' => 'required|integer',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $validation->getErrors(),
            ]);
        }

        // Ambil data produk dari input POST
        $data = [
            'nama_produk' => $this->request->getPost('nama_produk'),
            'harga' => $this->request->getPost('harga'),
            'stok' => $this->request->getPost('stok'),
        ];

        // Simpan data produk ke dalam database
        $this->produkmodel->save($data);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Data produk berhasil disimpan',
        ]);
    }

    public function tampil_produk()
    {
        // Ambil semua data produk dari model
        $produk = $this->produkmodel->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'produk' => $produk
        ]);
    }

    public function hapus_produk($id)
    {
        // Cek apakah produk dengan ID yang diberikan ada di database
        $produk = $this->produkmodel->find($id);
        if (!$produk) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Produk tidak ditemukan',
            ]);
        }

        // Hapus produk
        if ($this->produkmodel->delete($id)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Produk berhasil dihapus',
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal menghapus produk',
            ]);
        }
    }

    public function update()
    {
        // Mendapatkan ID produk dari input
        $id = $this->request->getVar('produk_id');

        // Validasi input dari AJAX
        $validation = \Config\Services::validation();

        $validation->setRules([
            'nama_produk' => 'required',
            'harga' => 'required|decimal',
            'stok' => 'required|integer',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $validation->getErrors(),
            ]);
        }

        // Mengambil data yang akan diupdate dari input
        $data = [
            'nama_produk' => $this->request->getVar('nama_produk'),
            'harga' => $this->request->getVar('harga'),
            'stok' => $this->request->getVar('stok'),
        ];

        // Update produk di model
        $updateStatus = $this->produkmodel->update($id, $data);

        // Jika update berhasil
        if ($updateStatus) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data produk berhasil diupdate',
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal memperbarui data produk',
            ]);
        }
    }

    // Fungsi untuk mengambil detail produk berdasarkan ID
    public function get_produk($id)
    {
        // Ambil data produk berdasarkan ID
        $produk = $this->produkmodel->find($id);

        // Jika produk ditemukan
        if ($produk) {
            return $this->response->setJSON([
                'status' => 'success',
                'produk' => $produk,
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Produk tidak ditemukan',
            ]);
        }
    }
}
