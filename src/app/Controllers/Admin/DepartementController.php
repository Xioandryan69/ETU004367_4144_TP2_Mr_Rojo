<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DepartementModel;
use App\Models\EmployeModel;

class DepartementController extends BaseController
{
    public function index()
    {
        $guard = $this->requireRole('admin');
        if ($guard !== null) {
            return $guard;
        }

        $departements = model(DepartementModel::class)->orderBy('nom', 'ASC')->findAll();

        return view('admin/departements/index', [
            'title' => 'Departements',
            'breadcrumb' => 'Departements',
            'departements' => $departements,
        ]);
    }

    public function create()
    {
        $guard = $this->requireRole('admin');
        if ($guard !== null) {
            return $guard;
        }

        $nom = trim((string) $this->request->getPost('nom'));
        $description = trim((string) $this->request->getPost('description'));

        if ($nom === '') {
            session()->setFlashdata('error', 'Nom obligatoire.');
            return redirect()->to('/admin/departements');
        }

        model(DepartementModel::class)->insert([
            'nom' => $nom,
            'description' => $description !== '' ? $description : null,
        ]);

        session()->setFlashdata('success', 'Departement cree.');
        return redirect()->to('/admin/departements');
    }

    public function edit(int $id)
    {
        $guard = $this->requireRole('admin');
        if ($guard !== null) {
            return $guard;
        }

        $departement = model(DepartementModel::class)->find($id);
        if (!$departement) {
            session()->setFlashdata('error', 'Departement introuvable.');
            return redirect()->to('/admin/departements');
        }

        return view('admin/departements/editer', [
            'title' => 'Editer departement',
            'breadcrumb' => 'Departements',
            'departement' => $departement,
        ]);
    }

    public function update(int $id)
    {
        $guard = $this->requireRole('admin');
        if ($guard !== null) {
            return $guard;
        }

        $nom = trim((string) $this->request->getPost('nom'));
        $description = trim((string) $this->request->getPost('description'));

        model(DepartementModel::class)->update($id, [
            'nom' => $nom,
            'description' => $description !== '' ? $description : null,
        ]);

        session()->setFlashdata('success', 'Departement mis a jour.');
        return redirect()->to('/admin/departements');
    }

    public function delete(int $id)
    {
        $guard = $this->requireRole('admin');
        if ($guard !== null) {
            return $guard;
        }

        $count = model(EmployeModel::class)->where('departement_id', $id)->countAllResults();
        if ($count > 0) {
            session()->setFlashdata('error', 'Impossible de supprimer : employes rattaches.');
            return redirect()->to('/admin/departements');
        }

        model(DepartementModel::class)->delete($id);
        session()->setFlashdata('success', 'Departement supprime.');
        return redirect()->to('/admin/departements');
    }
}
