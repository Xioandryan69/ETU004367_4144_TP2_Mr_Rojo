<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<div class="data-card">
    <div class="data-card-head">
        <h3>Demandes a traiter</h3>
        <div class="form-inline">
            <div class="filter-tabs">
                <a class="tab-pill <?= $statut === 'tous' ? 'active' : '' ?>" href="/rh?statut=tous&departement_id=<?= esc($departementId) ?>">Tous</a>
                <a class="tab-pill <?= $statut === 'en_attente' ? 'active' : '' ?>" href="/rh?statut=en_attente&departement_id=<?= esc($departementId) ?>">En attente</a>
                <a class="tab-pill <?= $statut === 'approuvee' ? 'active' : '' ?>" href="/rh?statut=approuvee&departement_id=<?= esc($departementId) ?>">Approuvees</a>
                <a class="tab-pill <?= $statut === 'refusee' ? 'active' : '' ?>" href="/rh?statut=refusee&departement_id=<?= esc($departementId) ?>">Refusees</a>
            </div>
            <form method="get" action="/rh" class="form-inline">
                <input type="hidden" name="statut" value="<?= esc($statut) ?>"/>
                <select class="f-select" name="departement_id" onchange="this.form.submit()">
                    <option value="0">Tous les departements</option>
                    <?php foreach ($departements as $departement) : ?>
                        <option value="<?= esc($departement['id']) ?>" <?= (int) $departementId === (int) $departement['id'] ? 'selected' : '' ?>>
                            <?= esc($departement['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
    </div>
    <table class="tbl">
        <thead>
            <tr>
                <th>Employe</th>
                <th>Type</th>
                <th>Periode</th>
                <th>Duree</th>
                <th>Solde dispo</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($demandes) : ?>
                <?php foreach ($demandes as $demande) : ?>
                    <tr>
                        <td class="td-name">
                            <?= esc($demande['prenom'] . ' ' . $demande['nom']) ?><br/>
                            <span class="td-muted"><?= esc($demande['departement_nom'] ?? '-') ?></span>
                        </td>
                        <td>
                            <?php
                            $typeLabel = strtolower($demande['libelle']);
                            $typeClass = match ($typeLabel) {
                                'conge annuel' => 't-annuel',
                                'maladie' => 't-maladie',
                                'formation' => 't-special',
                                default => 't-special',
                            };
                            ?>
                            <span class="type-badge <?= esc($typeClass) ?>"><?= esc($demande['libelle']) ?></span>
                        </td>
                        <td class="td-muted"><?= esc($demande['date_debut']) ?> - <?= esc($demande['date_fin']) ?></td>
                        <td class="td-mono"><?= esc($demande['nb_jours']) ?> j</td>
                        <td class="td-mono">
                            <?php if ($demande['restant'] !== null) : ?>
                                <?= esc($demande['restant']) ?> j
                            <?php else : ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                            $statutClass = match ($demande['statut']) {
                                'approuvee' => 's-approuvee',
                                'refusee' => 's-refusee',
                                default => 's-attente',
                            };
                            ?>
                            <span class="statut <?= esc($statutClass) ?>"><?= esc($demande['statut']) ?></span>
                        </td>
                        <td>
                            <?php if ($demande['statut'] === 'en_attente') : ?>
                                <div class="table-actions">
                                    <form method="post" action="/rh/demandes/<?= esc($demande['id']) ?>/approuver">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn-sm btn-approve"><i class="bi bi-check-lg"></i> Approuver</button>
                                    </form>
                                    <a class="btn-sm btn-refuse" href="/rh?statut=<?= esc($statut) ?>&departement_id=<?= esc($departementId) ?>&refuser=<?= esc($demande['id']) ?>">
                                        <i class="bi bi-x-lg"></i> Refuser
                                    </a>
                                </div>
                            <?php else : ?>
                                <span class="td-muted">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="7">
                        <div class="empty">
                            <i class="bi bi-clipboard-check"></i>
                            <p>Aucune demande.</p>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if (!empty($refuserDemande)) : ?>
    <div class="data-card refuse-card">
        <div class="data-card-head">
            <h3>Confirmer le refus — <?= esc($refuserDemande['prenom'] . ' ' . $refuserDemande['nom']) ?></h3>
        </div>
        <div class="content" style="padding:1rem 1.25rem">
            <p class="td-muted">Demande de <?= esc($refuserDemande['nb_jours']) ?> jours du <?= esc($refuserDemande['date_debut']) ?> au <?= esc($refuserDemande['date_fin']) ?> · Type : <?= esc($refuserDemande['libelle']) ?></p>
            <form method="post" action="/rh/demandes/<?= esc($refuserDemande['id']) ?>/refuser">
                <?= csrf_field() ?>
                <div class="f-group">
                    <label class="f-label">Commentaire pour l'employe (optionnel)</label>
                    <textarea class="f-textarea" name="commentaire_rh" placeholder="Motif du refus..."></textarea>
                </div>
                <div class="form-actions">
                    <button class="btn-sm btn-refuse" type="submit"><i class="bi bi-x-lg"></i> Confirmer le refus</button>
                    <a class="btn-sm btn-del" href="/rh?statut=<?= esc($statut) ?>&departement_id=<?= esc($departementId) ?>">Annuler</a>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>
