<?php

namespace App\Models;

use CodeIgniter\Model;

class produkModel extends Model
{
  protected $table              = 'tb_produk';
  protected $primaryKey         = 'produk_id';
  protected $useAutoIncrement   = true;
  protected $returnType         = 'array';
  protected $useSoftDeletes     = false;
  protected $protectFields      = true;
  protected $allowedFields      = ['nama_produk', 'harga', 'stok'];

  //dates
  protected $useTimestamps  = false;
  protected $dateFormat     = 'datetime';
  protected $createdField   = 'created_at';
  protected $updatedField   = 'updated_at';
  protected $deletedField   = 'deleted_at';
}