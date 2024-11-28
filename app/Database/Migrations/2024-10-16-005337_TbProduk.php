<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TbProduk extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'produk_id'         =>[
                'type'          => 'INT',
                'contraint'     => 11,
                'usigned'       => true,
                'auto_increment' => true,
            ],
            'nama_produk'         =>[
                'type'          => 'VARCHAR',
                'contraint'     => 255,
            ],
            'harga'         =>[
                'type'          => 'DECIMAL',
                'contraint'     => 10,2,
            ],
            'stok'         =>[
                'type'          => 'INT',
                'contraint'     => 11,
            ],
        ]);
        $this->forge->addPrimaryKey('produk_id', true);
        $this->forge->createTable('tb_produk');

    }

    public function down()
    {
        $this->forge->dropTable('tb_produk');
    }
}
