<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TypesCongeSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'libelle' => 'Conge annuel',
                'jours_annuels' => 25,
                'deductible' => 1,
            ],
            [
                'libelle' => 'Maladie',
                'jours_annuels' => 15,
                'deductible' => 1,
            ],
            [
                'libelle' => 'Formation',
                'jours_annuels' => 5,
                'deductible' => 0,
            ],
        ];

        $this->db->table('types_conge')->insertBatch($data);
    }
}
