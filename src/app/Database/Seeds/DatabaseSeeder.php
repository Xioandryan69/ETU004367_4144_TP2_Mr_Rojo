<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. Départements
        $dataDepartements = [
            ['id' => 1, 'nom' => 'Informatique', 'description' => 'Service IT'],
            ['id' => 2, 'nom' => 'Ressources Humaines', 'description' => 'Service RH'],
        ];
        $this->db->table('departements')->ignore(true)->insertBatch($dataDepartements);

        // 2. Types de congé
        $dataTypes = [
            ['id' => 1, 'libelle' => 'Congé Annuel', 'jours_annuels' => 30, 'deductible' => 1],
            ['id' => 2, 'libelle' => 'Maladie', 'jours_annuels' => 15, 'deductible' => 1],
            ['id' => 3, 'libelle' => 'Congé Spécial', 'jours_annuels' => 0, 'deductible' => 0],
            ['id' => 4, 'libelle' => 'Sans Solde', 'jours_annuels' => 0, 'deductible' => 0],
        ];
        $this->db->table('types_conge')->ignore(true)->insertBatch($dataTypes);

        // 3. Employés
        $dataEmployes = [
            [
                'id' => 1,
                'nom' => 'Admin', 'prenom' => 'Super', 'email' => 'admin@techmada.mg',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin', 'departement_id' => 1, 'date_embauche' => '2020-01-01', 'actif' => 1
            ],
            [
                'id' => 2,
                'nom' => 'ROZ', 'prenom' => 'Jeanne', 'email' => 'rh@techmada.mg',
                'password' => password_hash('rh123', PASSWORD_DEFAULT),
                'role' => 'rh', 'departement_id' => 2, 'date_embauche' => '2021-01-01', 'actif' => 1
            ],
            [
                'id' => 3,
                'nom' => 'ANDRIA', 'prenom' => 'Koto', 'email' => 'employe@techmada.mg',
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'role' => 'employe', 'departement_id' => 1, 'date_embauche' => '2022-01-01', 'actif' => 1
            ]
        ];
        $this->db->table('employes')->ignore(true)->insertBatch($dataEmployes);

        // 4. Soldes pour l'employé simple (ID = 3)
        $dataSoldes = [
            ['employe_id' => 3, 'type_conge_id' => 1, 'annee' => date('Y'), 'jours_attribues' => 30, 'jours_pris' => 12],
            ['employe_id' => 3, 'type_conge_id' => 2, 'annee' => date('Y'), 'jours_attribues' => 15, 'jours_pris' => 2],
        ];
        $this->db->table('soldes')->ignore(true)->insertBatch($dataSoldes);

        // 5. Demandes de congés
        $dataConges = [
            [
                'employe_id' => 3,
                'type_conge_id' => 1,
                'date_debut' => date('Y-m-d', strtotime('+5 days')),
                'date_fin' => date('Y-m-d', strtotime('+10 days')),
                'nb_jours' => 5,
                'motif' => 'Voyage en famille',
                'statut' => 'en_attente',
                'commentaire_rh' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'traite_par' => null
            ],
            [
                'employe_id' => 3,
                'type_conge_id' => 2,
                'date_debut' => date('Y-m-d', strtotime('-15 days')),
                'date_fin' => date('Y-m-d', strtotime('-13 days')),
                'nb_jours' => 2,
                'motif' => 'Grippe',
                'statut' => 'approuvee',
                'commentaire_rh' => 'Bon rétablissement',
                'created_at' => date('Y-m-d H:i:s', strtotime('-16 days')),
                'traite_par' => 2
            ]
        ];
        $this->db->table('conges')->ignore(true)->insertBatch($dataConges);
    }
}