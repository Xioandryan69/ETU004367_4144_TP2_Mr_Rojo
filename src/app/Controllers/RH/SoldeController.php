<?php

namespace App\Controllers\RH;

use App\Controllers\BaseController;
use App\Models\SoldeModel;

class SoldeController extends BaseController
{
    public function index()
    {
        $guard = $this->requireAnyRole(['rh', 'admin']);
        if ($guard !== null) {
            return $guard;
        }

        $annee = (int) date('Y');
        $soldes = model(SoldeModel::class)
            ->select('soldes.*, employes.nom, employes.prenom, departements.nom as departement_nom, types_conge.libelle')
            ->join('employes', 'employes.id = soldes.employe_id')
            ->join('departements', 'departements.id = employes.departement_id', 'left')
            ->join('types_conge', 'types_conge.id = soldes.type_conge_id')
            ->where('soldes.annee', $annee)
            ->orderBy('employes.nom', 'ASC')
            ->findAll();

        foreach ($soldes as &$solde) {
            $solde['restant'] = (int) $solde['jours_attribues'] - (int) $solde['jours_pris'];
        }
        unset($solde);

        return view('rh/soldes/index', [
            'title' => 'Soldes employes',
            'breadcrumb' => 'Soldes employes',
            'annee' => $annee,
            'soldes' => $soldes,
        ]);
    }
}
