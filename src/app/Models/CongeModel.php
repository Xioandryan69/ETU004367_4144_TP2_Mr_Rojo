<?php

namespace App\Models;

use CodeIgniter\Model;

class CongeModel extends Model
{
    protected $table = 'conges';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'employe_id',
        'type_conge_id',
        'date_debut',
        'date_fin',
        'nb_jours',
        'motif',
        'statut',
        'commentaire_rh',
        'created_at',
        'traite_par',
    ];

    protected $validationRules = [
        'employe_id' => 'required|is_natural_no_zero',
        'type_conge_id' => 'required|is_natural_no_zero',
        'date_debut' => 'required|valid_date',
        'date_fin' => 'required|valid_date',
        'nb_jours' => 'required|is_natural_no_zero',
        'statut' => 'required|in_list[en_attente,approuvee,refusee,annulee]',
    ];
}
