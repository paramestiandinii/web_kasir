<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PelangganModel;

class PelangganCopy extends BaseController
{
    protected $pelangganModel;

    public function __construct()
    {
        // Inisialisasi model Pelanggan
        $this->pelangganModel = new PelangganModel();
    }

    // Menampilkan halaman beranda pelanggan
    public function index()
    {
        // Menampilkan view beranda
        return view('berandaPelanggan');
    }

    // Menyimpan data pelanggan
    public function simpan_pelanggan()
{
    // Validasi input dari AJAX
    $validation = \Config\Services::validation();
    $validation->setRules([
        'nama_pelanggan' => 'required|min_length[3]',  // Aturan: wajib diisi dan minimal 3 karakter
        'alamat' => 'required|min_length[10]',  // Aturan: wajib diisi dan minimal 10 karakter
        'telepon' => 'required|numeric|min_length[10]',  // Aturan: wajib diisi, hanya angka, dan minimal 10 digit
    ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $validation->getErrors(),
            ]);
        }

        // Ambil data pelanggan dari input POST
        $data = [
            'nama_pelanggan' => $this->request->getPost('nama_pelanggan'),
            'alamat' => $this->request->getPost('alamat'),
            'telepon' => $this->request->getPost('telepon'),
        ];

        // Simpan data pelanggan ke dalam database
        $this->pelangganModel->save($data);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Data pelanggan berhasil disimpan',
        ]);
    }

    // Menampilkan semua data pelanggan
    public function tampil_pelanggan()
    {
        // Ambil semua data pelanggan dari model
        $pelanggan = $this->pelangganModel->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'pelanggan' => $pelanggan,
        ]);
    }

    // Menghapus data pelanggan berdasarkan ID
    public function hapus_pelanggan($id)
    {
        // Cek apakah pelanggan dengan ID yang diberikan ada di database
        $pelanggan = $this->pelangganModel->find($id);
        if (!$pelanggan) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Pelanggan tidak ditemukan',
            ]);
        }

        // Hapus pelanggan
        if ($this->pelangganModel->delete($id)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Pelanggan berhasil dihapus',
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal menghapus pelanggan',
            ]);
        }
    }

    // Mengupdate data pelanggan
    public function update()
    {
        // Mendapatkan ID pelanggan dari input
        $id = $this->request->getVar('pelanggan_id');

        // Validasi input dari AJAX
        $validation = \Config\Services::validation();

        $validation->setRules([
            'nama_pelanggan' => 'required',
            'alamat' => 'required',
            'telepon' => 'required',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $validation->getErrors(),
            ]);
        }

        // Mengambil data yang akan diupdate dari input
        $data = [
            'nama_pelanggan' => $this->request->getVar('nama_pelanggan'),
            'alamat' => $this->request->getVar('alamat'),
            'telepon' => $this->request->getVar('telepon'),
        ];

        // Update pelanggan di model
        $updateStatus = $this->pelangganModel->update($id, $data);

        // Jika update berhasil
        if ($updateStatus) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data pelanggan berhasil diperbarui',
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal memperbarui data pelanggan',
            ]);
        }
    }

    // Fungsi untuk mengambil detail pelanggan berdasarkan ID
    public function get_pelanggan($id)
    {
        // Ambil data pelanggan berdasarkan ID
        $pelanggan = $this->pelangganModel->find($id);

        // Jika pelanggan ditemukan
        if ($pelanggan) {
            return $this->response->setJSON([
                'status' => 'success',
                'pelanggan' => $pelanggan,
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Pelanggan tidak ditemukan',
            ]);
        }
    }
}
