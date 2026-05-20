<?php

namespace App\Controllers;
use App\Models\CongeModel;
use App\Models\EmployeModel;
use App\Models\SoldeModel;
use App\Models\TypeCongeModel;

class CalendrierController extends BaseController
{
    public function index()
    {
        $session = session();
        $employeId = $session->get('id');
        $congeModel = new CongeModel();
        $soldeModel = new SoldeModel();
        $typeCongeModel = new TypeCongeModel();
        $data['conges'] = $congeModel->getCongesByEmploye($employeId);
        $data['soldes'] = $soldeModel->getSoldesByEmploye($employeId);
        $data['typesConge'] = $typeCongeModel->findAll();
        /*
        if (session()->get('isLoggedIn')) {
            $role = session()->get('role');
            switch ($role) {
                case 'admin':
                    return redirect()->to('/admin');
                case 'rh':
                    return redirect()->to('/rh');
                case 'employe':
                    return redirect()->to('/employe');
                default:
                    return redirect()->to('/login');
            }
        }
        */

        return view('employe/calendar', $data);
    }

    public function events()
    {
        $session = session();
        $employId = $session->get('id');
        $model = new CongeModel();

        $conges = $model->where('employe_id', $employId)->findAll();
        $events = [];
        foreach ($conges as $c) {
            $color = match ($c['statut']) {
                'approuvee' => '#2ecc71',
                'refusee' => '#e74c3c',
                'en_attente' => '#f39c12',
                default => '#3498db'
            };

            $events[] = [
                'title' => 'Congé (' . $c['statut'] . ')',
                'start' => $c['date_debut'],
                'end' => $c['date_fin'],
                'color' => $color
            ];
        }
        return $this->response->setJSON($events);
    }
}
