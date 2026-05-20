<?php

namespace App\Controllers\Employe;

use App\Controllers\BaseController;
use App\Models\SoldeModel;

class SoldeController extends BaseController
{
    public function index()
    {
        $guard = $this->requireRole('employe');
        if ($guard !== null) {
            return $guard;
        }

        $employeId = (int) session()->get('employe_id');
        $annee = (int) date('Y');

        $soldes = model(\App\Models\TypeCongeModel::class)
            ->select('types_conge.libelle, types_conge.deductible, COALESCE(soldes.jours_attribues, types_conge.jours_annuels) as jours_attribues, COALESCE(soldes.jours_pris, 0) as jours_pris')
            ->join('soldes', "soldes.type_conge_id = types_conge.id AND soldes.employe_id = $employeId AND soldes.annee = $annee", 'left')
            ->findAll();

        foreach ($soldes as &$solde) {
            $solde['restant'] = (int) $solde['jours_attribues'] - (int) $solde['jours_pris'];
        }
        unset($solde);

        return view('employe/soldes/index', [
            'title' => 'Mes soldes',
            'breadcrumb' => 'Mes soldes',
            'annee' => $annee,
            'soldes' => $soldes,
        ]);
    }
}
