<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SoldesSeeder extends Seeder
{
    public function run()
    {
        $annee = (int) date('Y');

        $employes = $this->db->table('employes')->get()->getResultArray();
        $types = $this->db->table('types_conge')
            ->where('deductible', 1)
            ->get()
            ->getResultArray();

        $rows = [];
        foreach ($employes as $employe) {
            foreach ($types as $type) {
                $rows[] = [
                    'employe_id' => $employe['id'],
                    'type_conge_id' => $type['id'],
                    'annee' => $annee,
                    'jours_attribues' => $type['jours_annuels'],
                    'jours_pris' => 0,
                ];
            }
        }

        if ($rows !== []) {
            $this->db->table('soldes')->insertBatch($rows);
        }
    }
}
