<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TbPelanggan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'pelanggan_id'  => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_pelanggan' => [
                'type'       => 'VARCHAR',
                'constraint' => 225,
            ],
            'alamat' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'telepon' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
        ]);

        $this->forge->addKey('pelanggan_id', true); // Set pelanggan_id as primary key
        $this->forge->createTable('tb_pelanggan'); // Create the table
    }

    public function down()
    {
        $this->forge->dropTable('tb_pelanggan');
    }
}
