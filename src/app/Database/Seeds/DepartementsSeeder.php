<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DepartementsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nom' => 'Informatique',
                'description' => 'Infrastructure, support et developpement applicatif.',
            ],
            [
                'nom' => 'Ressources Humaines',
                'description' => 'Gestion des talents, paie et administration du personnel.',
            ],
            [
                'nom' => 'Finance',
                'description' => 'Comptabilite, budget et controle financier.',
            ],
        ];

        $this->db->table('departements')->insertBatch($data);
    }
}
