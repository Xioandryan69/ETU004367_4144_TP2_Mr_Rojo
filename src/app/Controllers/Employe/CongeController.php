<?php

namespace App\Controllers\Employe;

use App\Controllers\BaseController;
use App\Models\CongeModel;
use App\Models\SoldeModel;
use App\Models\TypeCongeModel;
use DateTime;

class CongeController extends BaseController
{
    public function index()
    {
        $guard = $this->requireRole('employe');
        if ($guard !== null) {
            return $guard;
        }

        $employeId = (int) session()->get('employe_id');

        $statut = (string) $this->request->getGet('statut');
        $statut = $statut === '' ? 'tous' : $statut;
        $allowed = ['tous', 'en_attente', 'approuvee', 'refusee', 'annulee'];
        if (!in_array($statut, $allowed, true)) {
            $statut = 'tous';
        }

        $builder = model(CongeModel::class)
            ->select('conges.*, types_conge.libelle')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id')
            ->where('conges.employe_id', $employeId)
            ->orderBy('conges.created_at', 'DESC');

        if ($statut !== 'tous') {
            $builder->where('conges.statut', $statut);
        }

        return view('employe/conges/liste', [
            'title' => 'Mes demandes',
            'breadcrumb' => 'Mes demandes',
            'conges' => $builder->findAll(),
            'statut' => $statut,
        ]);
    }

    public function create()
    {
        $guard = $this->requireRole('employe');
        if ($guard !== null) {
            return $guard;
        }

        $employeId = (int) session()->get('employe_id');
        $annee = (int) date('Y');

        $types = model(TypeCongeModel::class)->orderBy('libelle', 'ASC')->findAll();

        $soldes = model(SoldeModel::class)
            ->select('soldes.*, types_conge.libelle, types_conge.deductible')
            ->join('types_conge', 'types_conge.id = soldes.type_conge_id')
            ->where('soldes.employe_id', $employeId)
            ->where('soldes.annee', $annee)
            ->orderBy('types_conge.libelle', 'ASC')
            ->findAll();

        $soldeMap = [];
        foreach ($soldes as $solde) {
            $soldeMap[(int) $solde['type_conge_id']] = [
                'attribues' => (int) $solde['jours_attribues'],
                'pris' => (int) $solde['jours_pris'],
                'restant' => (int) $solde['jours_attribues'] - (int) $solde['jours_pris'],
                'deductible' => (int) ($solde['deductible'] ?? 0),
                'libelle' => (string) ($solde['libelle'] ?? ''),
            ];
        }

        return view('employe/conges/creer', [
            'title' => 'Nouvelle demande',
            'breadcrumb' => 'Nouvelle demande',
            'types' => $types,
            'soldes' => $soldes,
            'soldeMap' => $soldeMap,
            'annee' => $annee,
        ]);
    }

    public function store()
    {
        $guard = $this->requireRole('employe');
        if ($guard !== null) {
            return $guard;
        }

        $session = session();
        $employeId = (int) $session->get('employe_id');
        $typeId = (int) $this->request->getPost('type_conge_id');
        $dateDebut = (string) $this->request->getPost('date_debut');
        $dateFin = (string) $this->request->getPost('date_fin');
        $motif = trim((string) $this->request->getPost('motif'));

        if ($typeId <= 0 || $dateDebut === '' || $dateFin === '') {
            $session->setFlashdata('error', 'Tous les champs obligatoires doivent etre remplis.');
            return redirect()->to('/employe/conges/creer');
        }

        $start = new DateTime($dateDebut);
        $end = new DateTime($dateFin);
        if ($start > $end) {
            $session->setFlashdata('error', 'La date de debut doit etre avant la date de fin.');
            return redirect()->to('/employe/conges/creer');
        }

        $nbJours = $start->diff($end)->days + 1;

        $congeModel = new CongeModel();
        $overlap = $congeModel->where('employe_id', $employeId)
            ->whereIn('statut', ['en_attente', 'approuvee'])
            ->groupStart()
            ->where('date_debut <=', $dateFin)
            ->where('date_fin >=', $dateDebut)
            ->groupEnd()
            ->countAllResults();
        if ($overlap > 0) {
            $session->setFlashdata('error', 'Une demande existe deja sur ces dates.');
            return redirect()->to('/employe/conges/creer');
        }

        $typeModel = new TypeCongeModel();
        $type = $typeModel->find($typeId);
        if (!$type) {
            $session->setFlashdata('error', 'Type de conge invalide.');
            return redirect()->to('/employe/conges/creer');
        }

        if ((int) $type['deductible'] === 1) {
            $annee = (int) date('Y');
            $solde = model(SoldeModel::class)
                ->where('employe_id', $employeId)
                ->where('type_conge_id', $typeId)
                ->where('annee', $annee)
                ->first();

            if (!$solde) {
                $session->setFlashdata('error', 'Aucun solde trouve pour ce type de conge.');
                return redirect()->to('/employe/conges/creer');
            }

            $restant = (int) $solde['jours_attribues'] - (int) $solde['jours_pris'];
            if ($restant < $nbJours) {
                $session->setFlashdata('error', 'Solde insuffisant pour cette demande.');
                return redirect()->to('/employe/conges/creer');
            }
        }

        $congeModel->insert([
            'employe_id' => $employeId,
            'type_conge_id' => $typeId,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'nb_jours' => $nbJours,
            'motif' => $motif !== '' ? $motif : null,
            'statut' => 'en_attente',
        ]);

        $session->setFlashdata('success', 'Demande envoyee.');
        return redirect()->to('/employe/conges');
    }

    public function annuler(int $id)
    {
        $guard = $this->requireRole('employe');
        if ($guard !== null) {
            return $guard;
        }

        $session = session();
        $employeId = (int) $session->get('employe_id');
        $congeModel = new CongeModel();
        $conge = $congeModel->where('id', $id)->where('employe_id', $employeId)->first();

        if (!$conge) {
            $session->setFlashdata('error', 'Demande introuvable.');
            return redirect()->to('/employe/conges');
        }

        if ($conge['statut'] !== 'en_attente') {
            $session->setFlashdata('error', 'Seules les demandes en attente peuvent etre annulees.');
            return redirect()->to('/employe/conges');
        }

        $congeModel->update($id, ['statut' => 'annulee']);
        $session->setFlashdata('success', 'Demande annulee.');
        return redirect()->to('/employe/conges');
    }
}
