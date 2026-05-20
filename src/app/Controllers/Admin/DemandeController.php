<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CongeModel;

class DemandeController extends BaseController
{
    public function index()
    {
        $guard = $this->requireRole('admin');
        if ($guard !== null) {
            return $guard;
        }

        $statut = (string) $this->request->getGet('statut');
        $statut = $statut === '' ? 'tous' : $statut;
        $allowed = ['tous', 'en_attente', 'approuvee', 'refusee', 'annulee'];
        if (!in_array($statut, $allowed, true)) {
            $statut = 'tous';
        }

        $builder = model(CongeModel::class)
            ->select('conges.*, employes.nom, employes.prenom, departements.nom as departement_nom, types_conge.libelle')
            ->join('employes', 'employes.id = conges.employe_id')
            ->join('departements', 'departements.id = employes.departement_id', 'left')
            ->join('types_conge', 'types_conge.id = conges.type_conge_id')
            ->orderBy('conges.created_at', 'DESC');

        if ($statut !== 'tous') {
            $builder->where('conges.statut', $statut);
        }

        return view('admin/demandes/liste', [
            'title' => 'Toutes les demandes',
            'breadcrumb' => 'Demandes',
            'demandes' => $builder->findAll(),
            'statut' => $statut,
        ]);
    }
}
