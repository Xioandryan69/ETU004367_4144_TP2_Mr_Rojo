<?php

namespace App\Controllers;

use App\Models\CongeModel;
use App\Models\DepartementModel;
use App\Models\SoldeModel;

class RhController extends BaseController
{
    public function dashboard()
    {
        return redirect()->to(site_url('rh'));
    }

    public function index()
    {
        helper('form');
        $congeModel = new CongeModel();
        $departementModel = new DepartementModel();

        $statut = $this->request->getGet('statut');
        $departementId = $this->request->getGet('departement_id');
        $departementId = $departementId ? (int) $departementId : null;

        $data['statutFiltre'] = $statut;
        $data['departementFiltre'] = $departementId;
        $data['demandes'] = $congeModel->getDemandes($statut ?: null, $departementId);
        $data['departements'] = $departementModel->findAll();

        $data['pendingCount'] = count($congeModel->getDemandes('en_attente', $departementId));

        return view('rh/index', $data);
    }

    public function approve(int $id)
    {
        $session = session();
        $congeModel = new CongeModel();
        $soldeModel = new SoldeModel();

        $demande = $congeModel->getDemandeDetails($id);
        if (!$demande) {
            return redirect()->back()->with('error', 'Demande introuvable.');
        }

        if ($demande['statut'] !== 'en_attente') {
            return redirect()->back()->with('error', 'Cette demande a déjà été traitée.');
        }

        if ((int) $demande['deductible'] === 1) {
            $ok = $soldeModel->addJoursPris((int) $demande['employe_id'], (int) $demande['type_conge_id'], (int) $demande['nb_jours']);
            if (!$ok) {
                return redirect()->back()->with('error', 'Impossible de mettre à jour le solde.');
            }
        }

        $congeModel->update($id, [
            'statut' => 'approuvee',
            'traite_par' => $session->get('id'),
            'commentaire_rh' => null,
        ]);

        return redirect()->back()->with('success', 'Demande approuvée et solde mis à jour.');
    }

    public function refuse(int $id)
    {
        $commentaire = trim((string) $this->request->getPost('commentaire_rh'));
        $congeModel = new CongeModel();
        $session = session();

        $demande = $congeModel->getDemandeDetails($id);
        if (!$demande) {
            return redirect()->back()->with('error', 'Demande introuvable.');
        }

        if ($demande['statut'] !== 'en_attente') {
            return redirect()->back()->with('error', 'Cette demande a déjà été traitée.');
        }

        $congeModel->update($id, [
            'statut' => 'refusee',
            'commentaire_rh' => $commentaire ?: 'Refusée',
            'traite_par' => $session->get('id'),
        ]);

        return redirect()->back()->with('success', 'Demande refusée.');
    }
}
