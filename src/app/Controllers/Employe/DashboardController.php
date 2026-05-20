<?php

namespace App\Controllers\Employe;

use App\Controllers\BaseController;
use App\Models\CongeModel;
use App\Models\SoldeModel;
use App\Models\TypeCongeModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $guard = $this->requireRole('employe');
        if ($guard !== null) {
            return $guard;
        }

        $employeId = (int) session()->get('employe_id');
        $annee = (int) date('Y');

        $congeModel = new CongeModel();
        $pending = $congeModel->where('employe_id', $employeId)->where('statut', 'en_attente')->countAllResults();
        $approved = $congeModel->where('employe_id', $employeId)->where('statut', 'approuvee')->countAllResults();
        $refused = $congeModel->where('employe_id', $employeId)->where('statut', 'refusee')->countAllResults();

        $soldeModel = new SoldeModel();
        $soldes = model(\App\Models\TypeCongeModel::class)
            ->select('types_conge.libelle, COALESCE(soldes.jours_attribues, types_conge.jours_annuels) as jours_attribues, COALESCE(soldes.jours_pris, 0) as jours_pris')
            ->join('soldes', "soldes.type_conge_id = types_conge.id AND soldes.employe_id = $employeId AND soldes.annee = $annee", 'left')
            ->findAll();

        $restant = 0;
        $totalAttribues = 0;
        foreach ($soldes as $solde) {
            $r = (int) $solde['jours_attribues'] - (int) $solde['jours_pris'];
            $restant += $r;
            $totalAttribues += (int) $solde['jours_attribues'];
        }

        $recentDemandes = $congeModel
            ->select('conges.*, types_conge.libelle')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id')
            ->where('conges.employe_id', $employeId)
            ->orderBy('conges.created_at', 'DESC')
            ->findAll(3);

        return view('employe/dashboard', [
            'title' => 'Tableau de bord',
            'breadcrumb' => 'Accueil',
            'pending' => $pending,
            'approved' => $approved,
            'refused' => $refused,
            'restant' => $restant,
            'totalAttribues' => $totalAttribues,
            'annee' => $annee,
            'soldes' => $soldes,
            'recentDemandes' => $recentDemandes,
        ]);
    }
}
