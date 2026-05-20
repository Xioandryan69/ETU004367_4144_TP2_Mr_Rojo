<?php

namespace App\Models;

use CodeIgniter\Model;

class CongeModel extends Model
{
    protected $table            = 'conges';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'employe_id', 'type_conge_id', 'date_debut', 'date_fin', 
        'nb_jours', 'motif', 'statut', 'commentaire_rh', 
        'created_at', 'traite_par'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    public function getCongesByEmploye($employeId)
    {
        return $this->select('conges.*, types_conge.libelle as type_conge')
                    ->join('types_conge', 'types_conge.id = conges.type_conge_id')
                    ->where('employe_id', $employeId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    public function getAllAttente()
    {
        return $this->select('conges.*, employes.nom, employes.prenom, types_conge.libelle as type_conge')
                    ->join('employes', 'employes.id = conges.employe_id')
                    ->join('types_conge', 'types_conge.id = conges.type_conge_id')
                    ->where('statut', 'en_attente')
                    ->orderBy('created_at', 'ASC')
                    ->findAll();
    }

    public function getDemandes(?string $statut = null, ?int $departementId = null): array
    {
        $builder = $this->select('conges.*, employes.nom, employes.prenom, employes.departement_id, departements.nom as departement, types_conge.libelle as type_conge')
            ->join('employes', 'employes.id = conges.employe_id')
            ->join('departements', 'departements.id = employes.departement_id', 'left')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id')
            ->orderBy('created_at', 'DESC');

        if ($statut) {
            $builder->where('conges.statut', $statut);
        }

        if ($departementId) {
            $builder->where('employes.departement_id', $departementId);
        }

        return $builder->findAll();
    }

    public function getDemandeDetails(int $id): ?array
    {
        $demande = $this->select('conges.*, employes.nom, employes.prenom, employes.departement_id, departements.nom as departement, types_conge.libelle as type_conge, types_conge.deductible')
            ->join('employes', 'employes.id = conges.employe_id')
            ->join('departements', 'departements.id = employes.departement_id', 'left')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id')
            ->where('conges.id', $id)
            ->first();

        return $demande ?: null;
    }

    public function hasOverlap(int $employeId, string $dateDebut, string $dateFin): bool
    {
        return $this->where('employe_id', $employeId)
            ->whereNotIn('statut', ['refusee', 'annulee'])
            ->groupStart()
                ->where('date_debut <=', $dateFin)
                ->where('date_fin >=', $dateDebut)
            ->groupEnd()
            ->countAllResults() > 0;
    }

    public function getCongeEmploye(int $congeId, int $employeId): ?array
    {
        $conge = $this->where('id', $congeId)
            ->where('employe_id', $employeId)
            ->first();

        return $conge ?: null;
    }
}
