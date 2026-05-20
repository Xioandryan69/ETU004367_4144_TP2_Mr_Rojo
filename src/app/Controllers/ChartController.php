<?php

namespace App\Controllers;
use App\Models\CongeModel;
use App\Models\EmployeModel;
use App\Models\SoldeModel;
use App\Models\TypeCongeModel;
class ChartController extends BaseController
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

        return view('employe/chart');
    }
}
