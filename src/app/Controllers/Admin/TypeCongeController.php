<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TypeCongeModel;

class TypeCongeController extends BaseController
{
    public function index()
    {
        $guard = $this->requireRole('admin');
        if ($guard !== null) {
            return $guard;
        }

        $types = model(TypeCongeModel::class)->orderBy('libelle', 'ASC')->findAll();

        return view('admin/types_conge/index', [
            'title' => 'Types de conge',
            'breadcrumb' => 'Types de conge',
            'types' => $types,
        ]);
    }

    public function create()
    {
        $guard = $this->requireRole('admin');
        if ($guard !== null) {
            return $guard;
        }

        $libelle = trim((string) $this->request->getPost('libelle'));
        $jours = (int) $this->request->getPost('jours_annuels');
        $deductible = (int) $this->request->getPost('deductible');

        if ($libelle === '' || $jours <= 0) {
            session()->setFlashdata('error', 'Libelle et jours obligatoires.');
            return redirect()->to('/admin/types-conge');
        }

        $db = db_connect();
        $db->transStart();

        $typeId = model(TypeCongeModel::class)->insert([
            'libelle' => $libelle,
            'jours_annuels' => $jours,
            'deductible' => $deductible,
        ]);

        // Initialiser les soldes pour tous les employes existants pour l'annee en cours
        $employes = model(\App\Models\EmployeModel::class)->where('role', 'employe')->findAll();
        if (!empty($employes)) {
            $annee = (int) date('Y');
            $rows = [];
            foreach ($employes as $emp) {
                $rows[] = [
                    'employe_id' => $emp['id'],
                    'type_conge_id' => $typeId,
                    'annee' => $annee,
                    'jours_attribues' => $jours,
                    'jours_pris' => 0,
                ];
            }
            model(\App\Models\SoldeModel::class)->insertBatch($rows);
        }

        $db->transComplete();

        session()->setFlashdata('success', 'Type de conge cree.');
        return redirect()->to('/admin/types-conge');
    }

    public function edit(int $id)
    {
        $guard = $this->requireRole('admin');
        if ($guard !== null) {
            return $guard;
        }

        $type = model(TypeCongeModel::class)->find($id);
        if (!$type) {
            session()->setFlashdata('error', 'Type introuvable.');
            return redirect()->to('/admin/types-conge');
        }

        return view('admin/types_conge/editer', [
            'title' => 'Editer type de conge',
            'breadcrumb' => 'Types de conge',
            'type' => $type,
        ]);
    }

    public function update(int $id)
    {
        $guard = $this->requireRole('admin');
        if ($guard !== null) {
            return $guard;
        }

        $libelle = trim((string) $this->request->getPost('libelle'));
        $jours = (int) $this->request->getPost('jours_annuels');
        $deductible = (int) $this->request->getPost('deductible');

        model(TypeCongeModel::class)->update($id, [
            'libelle' => $libelle,
            'jours_annuels' => $jours,
            'deductible' => $deductible,
        ]);

        session()->setFlashdata('success', 'Type de conge mis a jour.');
        return redirect()->to('/admin/types-conge');
    }
}
