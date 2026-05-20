<?php

namespace App\Controllers;

use App\Models\CongeModel;
use App\Models\DepartementModel;
use App\Models\EmployeModel;
use App\Models\SoldeModel;
use App\Models\TypeCongeModel;

class AdminController extends BaseController
{
    public function index()
    {
        $congeModel = new CongeModel();
        $employeModel = new EmployeModel();
        $departementModel = new DepartementModel();

        $data['pendingCount'] = count($congeModel->getDemandes('en_attente'));
        $data['employeCount'] = $employeModel->where('actif', 1)->countAllResults();
        $data['departementCount'] = $departementModel->countAllResults();
        $data['recentDemandes'] = $congeModel->getDemandes(null, null);

        return view('admin/dashboard', $data);
    }

    // ─── EMPLOYÉS ────────────────────────────────────────────────────────────

    public function employes()
    {
        helper('form');
        $employeModel = new EmployeModel();
        $departementModel = new DepartementModel();

        $data['departements'] = $departementModel->orderBy('nom', 'ASC')->findAll();
        $data['employes'] = $employeModel
            ->select('employes.*, departements.nom as departement')
            ->join('departements', 'departements.id = employes.departement_id', 'left')
            ->orderBy('employes.nom', 'ASC')
            ->findAll();

        return view('admin/employes', $data);
    }

    public function storeEmploye()
    {
        helper('form');
        $rules = [
            'prenom'         => 'required|min_length[2]',
            'nom'            => 'required|min_length[2]',
            'email'          => 'required|valid_email',
            'password'       => 'required|min_length[6]',
            'departement_id' => 'required|integer',
            'role'           => 'required|in_list[employe,rh,admin]',
            'date_embauche'  => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $employeModel    = new EmployeModel();
        $typeCongeModel  = new TypeCongeModel();
        $soldeModel      = new SoldeModel();

        $employeId = $employeModel->insert([
            'prenom'         => $this->request->getPost('prenom'),
            'nom'            => $this->request->getPost('nom'),
            'email'          => $this->request->getPost('email'),
            'password'       => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'           => $this->request->getPost('role'),
            'departement_id' => (int) $this->request->getPost('departement_id'),
            'date_embauche'  => $this->request->getPost('date_embauche'),
            'actif'          => 1,
        ], true);

        if ($employeId) {
            $types = $typeCongeModel->where('deductible', 1)->findAll();
            $soldes = [];
            foreach ($types as $type) {
                $soldes[] = [
                    'employe_id'    => $employeId,
                    'type_conge_id' => (int) $type['id'],
                    'annee'         => date('Y'),
                    'jours_attribues' => (int) $type['jours_annuels'],
                    'jours_pris'    => 0,
                ];
            }
            if ($soldes) {
                $soldeModel->insertBatch($soldes);
            }
        }

        return redirect()->back()->with('success', 'Employé créé avec succès.');
    }

    public function updateEmploye(int $id)
    {
        helper('form');
        $rules = [
            'prenom'         => 'required|min_length[2]',
            'nom'            => 'required|min_length[2]',
            'departement_id' => 'required|integer',
            'role'           => 'required|in_list[employe,rh,admin]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $employeModel = new EmployeModel();
        $data = [
            'prenom'         => $this->request->getPost('prenom'),
            'nom'            => $this->request->getPost('nom'),
            'role'           => $this->request->getPost('role'),
            'departement_id' => (int) $this->request->getPost('departement_id'),
        ];

        $password = $this->request->getPost('password');
        if (!empty($password) && strlen($password) >= 6) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $employeModel->update($id, $data);

        return redirect()->to(site_url('admin/employes'))->with('success', 'Employé mis à jour.');
    }

    public function toggleEmploye(int $id)
    {
        $employeModel = new EmployeModel();
        $employe = $employeModel->find($id);

        if (!$employe) {
            return redirect()->back()->with('error', 'Employé introuvable.');
        }

        $newStatus = $employe['actif'] ? 0 : 1;
        $employeModel->update($id, ['actif' => $newStatus]);

        $msg = $newStatus ? 'Employé réactivé.' : 'Employé désactivé.';
        return redirect()->back()->with('success', $msg);
    }

    // ─── DÉPARTEMENTS ─────────────────────────────────────────────────────────

    public function departements()
    {
        helper('form');
        $departementModel = new DepartementModel();
        $data['departements'] = $departementModel->orderBy('nom', 'ASC')->findAll();
        return view('admin/departements', $data);
    }

    public function storeDepartement()
    {
        helper('form');
        $rules = ['nom' => 'required|min_length[2]'];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $departementModel = new DepartementModel();
        $departementModel->insert([
            'nom'         => $this->request->getPost('nom'),
            'description' => $this->request->getPost('description'),
        ]);

        return redirect()->back()->with('success', 'Département ajouté.');
    }

    public function deleteDepartement(int $id)
    {
        $departementModel = new DepartementModel();
        $employeModel = new EmployeModel();

        $count = $employeModel->where('departement_id', $id)->countAllResults();
        if ($count > 0) {
            return redirect()->back()->with('error', 'Impossible de supprimer : des employés appartiennent à ce département.');
        }

        $departementModel->delete($id);
        return redirect()->back()->with('success', 'Département supprimé.');
    }

    // ─── TYPES DE CONGÉ ───────────────────────────────────────────────────────

    public function typesConges()
    {
        helper('form');
        $typeCongeModel = new TypeCongeModel();
        $data['types'] = $typeCongeModel->orderBy('libelle', 'ASC')->findAll();
        return view('admin/types_conge', $data);
    }

    public function storeTypeConge()
    {
        helper('form');
        $rules = [
            'libelle'      => 'required|min_length[2]',
            'jours_annuels' => 'required|integer',
            'deductible'   => 'required|in_list[0,1]',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $typeCongeModel = new TypeCongeModel();
        $typeCongeModel->insert([
            'libelle'       => $this->request->getPost('libelle'),
            'jours_annuels' => (int) $this->request->getPost('jours_annuels'),
            'deductible'    => (int) $this->request->getPost('deductible'),
        ]);

        return redirect()->back()->with('success', 'Type de congé ajouté.');
    }

    public function deleteTypeConge(int $id)
    {
        $typeCongeModel = new TypeCongeModel();
        $typeCongeModel->delete($id);
        return redirect()->back()->with('success', 'Type de congé supprimé.');
    }

    // ─── SOLDES ───────────────────────────────────────────────────────────────

    public function soldes()
    {
        $soldeModel = new SoldeModel();
        $employeModel = new EmployeModel();
        $typeCongeModel = new TypeCongeModel();

        $data['soldes'] = $soldeModel
            ->select('soldes.*, employes.nom, employes.prenom, types_conge.libelle as type_conge')
            ->join('employes', 'employes.id = soldes.employe_id')
            ->join('types_conge', 'types_conge.id = soldes.type_conge_id')
            ->orderBy('employes.nom', 'ASC')
            ->findAll();

        $data['employes'] = $employeModel->where('actif', 1)->orderBy('nom', 'ASC')->findAll();
        $data['typesConge'] = $typeCongeModel->where('deductible', 1)->findAll();

        return view('admin/soldes', $data);
    }

    public function updateSolde(int $id)
    {
        helper('form');
        $rules = [
            'jours_attribues' => 'required|integer|greater_than_equal_to[0]',
            'jours_pris'      => 'required|integer|greater_than_equal_to[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $soldeModel = new SoldeModel();
        $soldeModel->update($id, [
            'jours_attribues' => (int) $this->request->getPost('jours_attribues'),
            'jours_pris'      => (int) $this->request->getPost('jours_pris'),
        ]);

        return redirect()->back()->with('success', 'Solde mis à jour.');
    }
}
