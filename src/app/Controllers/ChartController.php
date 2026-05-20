<?php

namespace App\Controllers;
use App\Models\CongeModel;
use App\Models\SoldeModel;
use App\Models\TypeCongeModel;
class ChartController extends BaseController
{
    public function index()
    {
        $session = session();
        $employeId = (int) $session->get('id');
        $congeModel = new CongeModel();
        $soldeModel = new SoldeModel();
        $typeCongeModel = new TypeCongeModel();

        $conges = $congeModel->getCongesByEmploye($employeId);
        $soldes = $soldeModel->getSoldesByEmploye($employeId);
        $typesConge = $typeCongeModel->findAll();

        $currentYear = date('Y');
        $monthlyCounts = array_fill(1, 12, 0);
        $typeCounts = [];
        $statusCounts = [
            'en_attente' => 0,
            'approuvee' => 0,
            'refusee' => 0,
            'annulee' => 0,
        ];

        foreach ($conges as $conge) {
            $typeLabel = (string) ($conge['type_conge'] ?? 'Autre');
            $typeCounts[$typeLabel] = ($typeCounts[$typeLabel] ?? 0) + 1;

            $statut = (string) ($conge['statut'] ?? 'en_attente');
            if (! array_key_exists($statut, $statusCounts)) {
                $statusCounts[$statut] = 0;
            }
            $statusCounts[$statut]++;

            $dateDebut = $conge['date_debut'] ?? null;
            if ($dateDebut && date('Y', strtotime($dateDebut)) === $currentYear) {
                $monthIndex = (int) date('n', strtotime($dateDebut));
                $monthlyCounts[$monthIndex]++;
            }
        }

        $remainingDays = 0;
        foreach ($soldes as $solde) {
            $remainingDays += max(0, (int) $solde['jours_attribues'] - (int) $solde['jours_pris']);
        }

        $data = [
            'conges' => $conges,
            'soldes' => $soldes,
            'typesConge' => $typesConge,
            'monthlyLabels' => ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
            'monthlyCounts' => array_values($monthlyCounts),
            'typeLabels' => array_keys($typeCounts),
            'typeCounts' => array_values($typeCounts),
            'statusLabels' => array_keys($statusCounts),
            'statusCounts' => array_values($statusCounts),
            'totalConges' => count($conges),
            'remainingDays' => $remainingDays,
            'currentYear' => (int) $currentYear,
        ];

        return view('employe/chart', $data);
    }
}
