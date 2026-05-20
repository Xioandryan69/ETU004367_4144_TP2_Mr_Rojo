<?php

namespace App\Controllers;

use App\Models\CongeModel;
use App\Models\EmployeModel;
use App\Models\SoldeModel;
use App\Models\TypeCongeModel;

class EmployeController extends BaseController
{
    public function index()
    {
        $session = session();
        $employeId = $session->get('id');

        $congeModel = new CongeModel();
        $data['conges'] = $congeModel->getCongesByEmploye($employeId);

        return view('employe/index', $data);
    }

    public function dashboard()
    {
        $session = session();
        $employeId = $session->get('id');

        $soldeModel = new SoldeModel();
        $congeModel = new CongeModel();

        $data['soldes'] = $soldeModel->getSoldesByEmploye($employeId);
        $data['conges'] = $congeModel->getCongesByEmploye($employeId);

        return view('employe/dashboard', $data);
    }

    public function create()
    {
        helper('form');

        $session = session();
        $employeId = $session->get('id');

        $soldeModel = new SoldeModel();
        $typeCongeModel = new TypeCongeModel();

        $data['soldes'] = $soldeModel->getSoldesByEmploye($employeId);
        $data['typesConge'] = $typeCongeModel->findAll();

        return view('employe/create', $data);
    }

    public function store()
    {
        helper('form');
        $session = session();
        $employeId = $session->get('id');

        $rules = [
            'type_conge_id' => 'required|integer',
            'date_debut' => 'required|valid_date',
            'date_fin' => 'required|valid_date',
            'motif' => 'permit_empty|string',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $typeCongeId = (int) $this->request->getPost('type_conge_id');
        $dateDebut = $this->request->getPost('date_debut');
        $dateFin = $this->request->getPost('date_fin');
        $motif = trim((string) $this->request->getPost('motif'));

        if (strtotime($dateDebut) > strtotime($dateFin)) {
            return redirect()->back()->withInput()->with('error', 'La date de fin doit être postérieure à la date de début.');
        }

        $congeModel = new CongeModel();
        if ($congeModel->hasOverlap($employeId, $dateDebut, $dateFin)) {
            return redirect()->back()->withInput()->with('error', 'Une demande chevauche déjà cette période.');
        }

        $nbJours = $this->countWorkingDays($dateDebut, $dateFin);
        if ($nbJours <= 0) {
            return redirect()->back()->withInput()->with('error', 'La période sélectionnée ne contient aucun jour ouvrable.');
        }

        $soldeModel = new SoldeModel();
        $solde = $soldeModel->getSoldeForEmployeType($employeId, $typeCongeId);
        if ($solde && (int) $solde['deductible'] === 1) {
            $restant = (int) $solde['jours_attribues'] - (int) $solde['jours_pris'];
            if ($nbJours > $restant) {
                return redirect()->back()->withInput()->with('error', 'Solde insuffisant pour ce type de congé.');
            }
        }

        $congeModel->insert([
            'employe_id' => $employeId,
            'type_conge_id' => $typeCongeId,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'nb_jours' => $nbJours,
            'motif' => $motif,
            'statut' => 'en_attente',
        ]);

        return redirect()->to(site_url('employe/mes-conges'))->with('success', 'Votre demande a été envoyée.');
    }

    public function cancel(int $id)
    {
        $session = session();
        $employeId = $session->get('id');
        $congeModel = new CongeModel();

        $conge = $congeModel->getCongeEmploye($id, $employeId);
        if (!$conge) {
            return redirect()->back()->with('error', 'Demande introuvable.');
        }

        if ($conge['statut'] !== 'en_attente') {
            return redirect()->back()->with('error', 'Seules les demandes en attente peuvent être annulées.');
        }

        $congeModel->update($id, ['statut' => 'annulee']);

        return redirect()->back()->with('success', 'Votre demande a été annulée.');
    }

    public function profil()
    {
        helper('form');
        $session = session();
        $employeId = $session->get('id');

        $employeModel = new EmployeModel();
        $data['employe'] = $employeModel->find($employeId);

        return view('employe/profil', $data);
    }

    public function updateProfil()
    {
        helper('form');
        $session = session();
        $employeId = $session->get('id');

        $rules = [
            'prenom' => 'required|min_length[2]',
            'nom' => 'required|min_length[2]',
            'password' => 'permit_empty|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'prenom' => $this->request->getPost('prenom'),
            'nom' => $this->request->getPost('nom'),
        ];

        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $employeModel = new EmployeModel();
        $employeModel->update($employeId, $data);

        $session->set('nom', $data['prenom'] . ' ' . $data['nom']);

        return redirect()->back()->with('success', 'Profil mis à jour.');
    }

    private function countWorkingDays(string $dateDebut, string $dateFin): int
    {
        $start = new \DateTime($dateDebut);
        $end = new \DateTime($dateFin);
        $end->setTime(0, 0, 1);

        $days = 0;
        while ($start <= $end) {
            $weekday = (int) $start->format('N');
            if ($weekday < 6) {
                $days++;
            }
            $start->modify('+1 day');
        }

        return $days;
    }
}
