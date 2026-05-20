<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CongeModel;
use App\Models\DepartementModel;
use App\Models\EmployeModel;
use App\Models\SoldeModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $guard = $this->requireRole('admin');
        if ($guard !== null) {
            return $guard;
        }

        $start = date('Y-m-01');
        $end = date('Y-m-t');
        $today = date('Y-m-d');

        $pendingDemandes = model(CongeModel::class)->where('statut', 'en_attente')->countAllResults();

        $approvedThisMonth = model(CongeModel::class)
            ->where('statut', 'approuvee')
            ->groupStart()
            ->where('date_debut <=', $end)
            ->where('date_fin >=', $start)
            ->groupEnd()
            ->countAllResults();

        $absentsToday = model(CongeModel::class)
            ->select('employes.nom, employes.prenom, types_conge.libelle, conges.date_fin')
            ->join('employes', 'employes.id = conges.employe_id')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id')
            ->where('conges.statut', 'approuvee')
            ->where('conges.date_debut <=', $today)
            ->where('conges.date_fin >=', $today)
            ->orderBy('conges.date_fin', 'ASC')
            ->findAll();

        $recentDemandes = model(CongeModel::class)
            ->select('conges.*, employes.nom, employes.prenom, types_conge.libelle')
            ->join('employes', 'employes.id = conges.employe_id')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id')
            ->orderBy('conges.created_at', 'DESC')
            ->findAll(5);

        $absences = model(CongeModel::class)
            ->select('conges.*, employes.nom, employes.prenom, departements.nom as departement_nom')
            ->join('employes', 'employes.id = conges.employe_id')
            ->join('departements', 'departements.id = employes.departement_id', 'left')
            ->where('conges.statut', 'approuvee')
            ->groupStart()
            ->where('conges.date_debut <=', $end)
            ->where('conges.date_fin >=', $start)
            ->groupEnd()
            ->orderBy('departements.nom', 'ASC')
            ->findAll();

        $absencesByDept = [];
        foreach ($absences as $absence) {
            $dept = $absence['departement_nom'] ?? 'Non defini';
            if (!isset($absencesByDept[$dept])) {
                $absencesByDept[$dept] = [
                    'count' => 0,
                    'employes' => [],
                ];
            }
            $name = trim($absence['prenom'] . ' ' . $absence['nom']);
            if (!in_array($name, $absencesByDept[$dept]['employes'], true)) {
                $absencesByDept[$dept]['employes'][] = $name;
                $absencesByDept[$dept]['count']++;
            }
        }

        $totalEmployes = model(EmployeModel::class)
            ->where('role', 'employe')
            ->where('actif', 1)
            ->countAllResults();
        $totalDepartements = model(DepartementModel::class)->countAllResults();

        $soldeCritique = model(SoldeModel::class)
            ->select('soldes.employe_id, SUM(soldes.jours_attribues) as attribues, SUM(soldes.jours_pris) as pris')
            ->join('types_conge', 'types_conge.id = soldes.type_conge_id')
            ->where('soldes.annee', (int) date('Y'))
            ->where('types_conge.deductible', 1)
            ->groupBy('soldes.employe_id')
            ->findAll();

        $soldeCritiqueCount = 0;
        foreach ($soldeCritique as $solde) {
            $restant = (int) $solde['attribues'] - (int) $solde['pris'];
            if ($restant <= 2) {
                $soldeCritiqueCount++;
            }
        }

        return view('admin/dashboard', [
            'title' => 'Vue d\'ensemble',
            'breadcrumb' => 'Administration',
            'absencesByDept' => $absencesByDept,
            'totalEmployes' => $totalEmployes,
            'totalDepartements' => $totalDepartements,
            'pendingDemandes' => $pendingDemandes,
            'approvedThisMonth' => $approvedThisMonth,
            'absentsToday' => $absentsToday,
            'recentDemandes' => $recentDemandes,
            'soldeCritiqueCount' => $soldeCritiqueCount,
        ]);
    }
}
