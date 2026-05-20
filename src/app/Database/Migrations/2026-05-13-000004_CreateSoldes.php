<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSoldes extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'employe_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'type_conge_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'annee' => [
                'type'       => 'INT',
                'constraint' => 4,
            ],
            'jours_attribues' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'jours_pris' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('employe_id');
        $this->forge->addKey('type_conge_id');
        $this->forge->addForeignKey('employe_id', 'employes', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('type_conge_id', 'types_conge', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('soldes', true);
    }

    public function down()
    {
        $this->forge->dropTable('soldes', true);
    }
}
