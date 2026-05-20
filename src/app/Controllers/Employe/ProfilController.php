<?php

namespace App\Controllers\Employe;

use App\Controllers\BaseController;
use App\Models\EmployeModel;

class ProfilController extends BaseController
{
    public function index()
    {
        $guard = $this->requireRole('employe');
        if ($guard !== null) {
            return $guard;
        }

        $employeId = (int) session()->get('employe_id');
        $employe = model(EmployeModel::class)
            ->select('employes.*, departements.nom as departement_nom')
            ->join('departements', 'departements.id = employes.departement_id', 'left')
            ->find($employeId);

        if (!$employe) {
            session()->setFlashdata('error', 'Profil introuvable.');
            return redirect()->to('/employe');
        }

        return view('employe/profil', [
            'title' => 'Mon profil',
            'breadcrumb' => 'Mon profil',
            'employe' => $employe,
        ]);
    }
}
