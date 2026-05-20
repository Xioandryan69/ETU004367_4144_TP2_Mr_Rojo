<?php

namespace App\Controllers\RH;

use App\Controllers\BaseController;
use App\Models\CongeModel;
use App\Models\DepartementModel;
use App\Models\SoldeModel;
use App\Models\TypeCongeModel;

class DemandeController extends BaseController
{
    public function index()
    {
        $guard = $this->requireAnyRole(['rh', 'admin']);
        if ($guard !== null) {
            return $guard;
        }

        $annee = (int) date('Y');
        $statut = (string) $this->request->getGet('statut');
        $statut = $statut === '' ? 'en_attente' : $statut;
        $allowed = ['tous', 'en_attente', 'approuvee', 'refusee'];
        if (!in_array($statut, $allowed, true)) {
            $statut = 'en_attente';
        }

        $departementId = (int) $this->request->getGet('departement_id');
        $refuserId = (int) $this->request->getGet('refuser');

        $builder = model(CongeModel::class)
            ->select('conges.*, employes.nom, employes.prenom, employes.departement_id, departements.nom as departement_nom, types_conge.libelle, types_conge.deductible, soldes.jours_attribues, soldes.jours_pris')
            ->join('employes', 'employes.id = conges.employe_id')
            ->join('departements', 'departements.id = employes.departement_id', 'left')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id')
            ->join(
                'soldes',
                'soldes.employe_id = conges.employe_id AND soldes.type_conge_id = conges.type_conge_id AND soldes.annee = ' . $annee,
                'left',
                false
            )
            ->orderBy('conges.created_at', 'DESC');

        if ($statut !== 'tous') {
            $builder->where('conges.statut', $statut);
        }

        if ($departementId > 0) {
            $builder->where('employes.departement_id', $departementId);
        }

        $demandes = $builder->findAll();
        foreach ($demandes as &$demande) {
            if ((int) $demande['deductible'] === 1) {
                $attribues = (int) ($demande['jours_attribues'] ?? 0);
                $pris = (int) ($demande['jours_pris'] ?? 0);
                $demande['restant'] = $attribues - $pris;
            } else {
                $demande['restant'] = null;
            }
        }
        unset($demande);

        $departements = model(DepartementModel::class)->orderBy('nom', 'ASC')->findAll();

        $refuserDemande = null;
        if ($refuserId > 0) {
            foreach ($demandes as $demande) {
                if ((int) $demande['id'] === $refuserId) {
                    $refuserDemande = $demande;
                    break;
                }
            }
        }

        return view('rh/demandes/liste', [
            'title' => 'Demandes RH',
            'breadcrumb' => 'Demandes',
            'demandes' => $demandes,
            'statut' => $statut,
            'departements' => $departements,
            'departementId' => $departementId,
            'refuserDemande' => $refuserDemande,
        ]);
    }

    public function approuver(int $id)
    {
        $guard = $this->requireAnyRole(['rh', 'admin']);
        if ($guard !== null) {
            return $guard;
        }

        $session = session();
        $employeId = (int) $session->get('employe_id');

        $congeModel = new CongeModel();
        $conge = $congeModel->where('id', $id)->where('statut', 'en_attente')->first();
        if (!$conge) {
            $session->setFlashdata('error', 'Demande introuvable ou deja traitee.');
            return redirect()->to('/rh');
        }

        $type = model(TypeCongeModel::class)->find($conge['type_conge_id']);
        if (!$type) {
            $session->setFlashdata('error', 'Type de conge introuvable.');
            return redirect()->to('/rh');
        }

        $db = db_connect();
        $db->transStart();

        if ((int) $type['deductible'] === 1) {
            $soldeModel = new SoldeModel();
            $solde = $soldeModel->where('employe_id', $conge['employe_id'])
                ->where('type_conge_id', $conge['type_conge_id'])
                ->where('annee', (int) date('Y'))
                ->first();

            if (!$solde) {
                $db->transRollback();
                $session->setFlashdata('error', 'Solde introuvable.');
                return redirect()->to('/rh');
            }

            $joursPris = (int) $solde['jours_pris'];
            $joursAttribues = (int) $solde['jours_attribues'];
            $nbJours = (int) $conge['nb_jours'];
            if ($joursPris + $nbJours > $joursAttribues) {
                $db->transRollback();
                $session->setFlashdata('error', 'Solde insuffisant.');
                return redirect()->to('/rh');
            }

            $soldeModel->update($solde['id'], [
                'jours_pris' => $joursPris + $nbJours,
            ]);
        }

        $congeModel->update($id, [
            'statut' => 'approuvee',
            'traite_par' => $employeId,
        ]);

        $db->transComplete();
        if ($db->transStatus() === false) {
            $session->setFlashdata('error', 'Erreur lors de la validation.');
            return redirect()->to('/rh');
        }

        $session->setFlashdata('success', 'Demande approuvee.');
        return redirect()->to('/rh');
    }

    public function refuser(int $id)
    {
        $guard = $this->requireAnyRole(['rh', 'admin']);
        if ($guard !== null) {
            return $guard;
        }

        $session = session();
        $employeId = (int) $session->get('employe_id');
        $commentaire = trim((string) $this->request->getPost('commentaire_rh'));

        $congeModel = new CongeModel();
        $conge = $congeModel->where('id', $id)->where('statut', 'en_attente')->first();
        if (!$conge) {
            $session->setFlashdata('error', 'Demande introuvable ou deja traitee.');
            return redirect()->to('/rh');
        }

        $congeModel->update($id, [
            'statut' => 'refusee',
            'commentaire_rh' => $commentaire !== '' ? $commentaire : null,
            'traite_par' => $employeId,
        ]);

        $session->setFlashdata('success', 'Demande refusee.');
        return redirect()->to('/rh');
    }
}
