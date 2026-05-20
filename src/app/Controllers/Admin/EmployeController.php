<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\DepartementModel;
use App\Models\EmployeModel;
use App\Models\SoldeModel;
use App\Models\TypeCongeModel;

class EmployeController extends BaseController
{
    public function index()
    {
        $guard = $this->requireRole('admin');
        if ($guard !== null) {
            return $guard;
        }

        $search = trim((string) $this->request->getGet('q'));
        $departementId = (int) $this->request->getGet('departement_id');

        $builder = model(EmployeModel::class)
            ->select('employes.*, departements.nom as departement_nom')
            ->join('departements', 'departements.id = employes.departement_id', 'left')
            ->where('employes.role', 'employe')
            ->orderBy('employes.nom', 'ASC');

        if ($search !== '') {
            $builder->groupStart()
                ->like('employes.nom', $search)
                ->orLike('employes.prenom', $search)
                ->orLike('employes.email', $search)
                ->groupEnd();
        }

        if ($departementId > 0) {
            $builder->where('employes.departement_id', $departementId);
        }

        $employes = $builder->findAll();

        $departements = model(DepartementModel::class)->orderBy('nom', 'ASC')->findAll();

        $annee = (int) date('Y');
        $soldes = model(SoldeModel::class)
            ->select('employe_id, SUM(jours_attribues) as attribues, SUM(jours_pris) as pris')
            ->join('types_conge', 'types_conge.id = soldes.type_conge_id')
            ->where('soldes.annee', $annee)
            ->where('types_conge.deductible', 1)
            ->groupBy('employe_id')
            ->findAll();

        $soldeMap = [];
        foreach ($soldes as $solde) {
            $attribues = (int) $solde['attribues'];
            $pris = (int) $solde['pris'];
            $soldeMap[$solde['employe_id']] = [
                'attribues' => $attribues,
                'pris' => $pris,
                'restant' => $attribues - $pris,
            ];
        }

        foreach ($employes as &$employe) {
            $solde = $soldeMap[$employe['id']] ?? ['attribues' => 0, 'pris' => 0, 'restant' => 0];
            $employe['solde_attribues'] = $solde['attribues'];
            $employe['solde_pris'] = $solde['pris'];
            $employe['solde_restant'] = $solde['restant'];
        }
        unset($employe);

        return view('admin/employes/index', [
            'title' => 'Employes',
            'breadcrumb' => 'Employes',
            'employes' => $employes,
            'departements' => $departements,
            'search' => $search,
            'departementId' => $departementId,
            'annee' => $annee,
        ]);
    }

    public function create()
    {
        $guard = $this->requireRole('admin');
        if ($guard !== null) {
            return $guard;
        }

        $session = session();
        $data = [
            'nom' => trim((string) $this->request->getPost('nom')),
            'prenom' => trim((string) $this->request->getPost('prenom')),
            'email' => trim((string) $this->request->getPost('email')),
            'password' => (string) $this->request->getPost('password'),
            'role' => (string) $this->request->getPost('role'),
            'departement_id' => (int) $this->request->getPost('departement_id'),
            'date_embauche' => (string) $this->request->getPost('date_embauche'),
            'actif' => 1,
        ];

        if ($data['nom'] === '' || $data['prenom'] === '' || $data['email'] === '' || $data['password'] === '') {
            $session->setFlashdata('error', 'Tous les champs obligatoires doivent etre remplis.');
            return redirect()->to('/admin/employes');
        }

        $model = new EmployeModel();
        $existing = $model->where('email', $data['email'])->first();
        if ($existing) {
            $session->setFlashdata('error', 'Email deja utilise.');
            return redirect()->to('/admin/employes');
        }

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        $db = db_connect();
        $db->transStart();

        $model->insert($data);
        $employeId = (int) $model->getInsertID();

        $types = model(TypeCongeModel::class)->findAll();
        $rows = [];
        $annee = (int) date('Y');
        foreach ($types as $type) {
            $rows[] = [
                'employe_id' => $employeId,
                'type_conge_id' => $type['id'],
                'annee' => $annee,
                'jours_attribues' => $type['jours_annuels'],
                'jours_pris' => 0,
            ];
        }
        if ($rows !== []) {
            model(SoldeModel::class)->insertBatch($rows);
        }

        $db->transComplete();
        if ($db->transStatus() === false) {
            $session->setFlashdata('error', 'Erreur lors de la creation.');
            return redirect()->to('/admin/employes');
        }

        $session->setFlashdata('success', 'Employe cree.');
        return redirect()->to('/admin/employes');
    }

    public function edit(int $id)
    {
        $guard = $this->requireRole('admin');
        if ($guard !== null) {
            return $guard;
        }

        $model = new EmployeModel();
        $employe = $model->find($id);
        if (!$employe) {
            session()->setFlashdata('error', 'Employe introuvable.');
            return redirect()->to('/admin/employes');
        }

        $departements = model(DepartementModel::class)->orderBy('nom', 'ASC')->findAll();

        return view('admin/employes/editer', [
            'title' => 'Editer employe',
            'breadcrumb' => 'Employes',
            'employe' => $employe,
            'departements' => $departements,
        ]);
    }

    public function update(int $id)
    {
        $guard = $this->requireRole('admin');
        if ($guard !== null) {
            return $guard;
        }

        $model = new EmployeModel();
        $employe = $model->find($id);
        if (!$employe) {
            session()->setFlashdata('error', 'Employe introuvable.');
            return redirect()->to('/admin/employes');
        }

        $email = trim((string) $this->request->getPost('email'));
        $existing = $model->where('email', $email)->where('id !=', $id)->first();
        if ($existing) {
            session()->setFlashdata('error', 'Email deja utilise.');
            return redirect()->to('/admin/employes/' . $id . '/editer');
        }

        $payload = [
            'nom' => trim((string) $this->request->getPost('nom')),
            'prenom' => trim((string) $this->request->getPost('prenom')),
            'email' => $email,
            'role' => (string) $this->request->getPost('role'),
            'departement_id' => (int) $this->request->getPost('departement_id'),
            'date_embauche' => (string) $this->request->getPost('date_embauche'),
            'actif' => (int) $this->request->getPost('actif'),
        ];

        $password = (string) $this->request->getPost('password');
        if ($password !== '') {
            $payload['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $model->update($id, $payload);
        session()->setFlashdata('success', 'Employe mis a jour.');
        return redirect()->to('/admin/employes');
    }

    public function deactivate(int $id)
    {
        $guard = $this->requireRole('admin');
        if ($guard !== null) {
            return $guard;
        }

        $employe = model(EmployeModel::class)->find($id);
        if (!$employe) {
            session()->setFlashdata('error', 'Employe introuvable.');
            return redirect()->to('/admin/employes');
        }

        if ($employe['role'] === 'admin') {
            session()->setFlashdata('error', 'Impossible de desactiver un admin.');
            return redirect()->to('/admin/employes');
        }

        model(EmployeModel::class)->update($id, ['actif' => 0]);
        session()->setFlashdata('success', 'Employe desactive.');
        return redirect()->to('/admin/employes');
    }
}
