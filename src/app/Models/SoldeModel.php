<?php

namespace App\Models;

use CodeIgniter\Model;

class SoldeModel extends Model
{
    protected $table            = 'soldes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['employe_id', 'type_conge_id', 'annee', 'jours_attribues', 'jours_pris'];

    public function getSoldesByEmploye($employeId, $annee = null)
    {
        if (!$annee) {
            $annee = date('Y');
        }
        return $this->select('soldes.*, types_conge.libelle, types_conge.deductible')
                    ->join('types_conge', 'types_conge.id = soldes.type_conge_id')
                    ->where('employe_id', $employeId)
                    ->where('annee', $annee)
                    ->findAll();
    }

    public function getSoldeForEmployeType(int $employeId, int $typeCongeId, ?string $annee = null): ?array
    {
        if (!$annee) {
            $annee = date('Y');
        }

        $solde = $this->select('soldes.*, types_conge.libelle, types_conge.deductible')
            ->join('types_conge', 'types_conge.id = soldes.type_conge_id')
            ->where('employe_id', $employeId)
            ->where('type_conge_id', $typeCongeId)
            ->where('annee', $annee)
            ->first();

        return $solde ?: null;
    }

    public function addJoursPris(int $employeId, int $typeCongeId, int $jours, ?string $annee = null): bool
    {
        if (!$annee) {
            $annee = date('Y');
        }

        $solde = $this->getSoldeForEmployeType($employeId, $typeCongeId, $annee);
        if (!$solde) {
            return false;
        }

        $joursPris = (int) $solde['jours_pris'] + $jours;
        return (bool) $this->update($solde['id'], ['jours_pris' => $joursPris]);
    }
}
