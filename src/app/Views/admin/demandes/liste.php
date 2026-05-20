<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<div class="data-card">
    <div class="data-card-head">
        <h3>Toutes les demandes</h3>
        <div class="filter-tabs">
            <a class="tab-pill <?= $statut === 'tous' ? 'active' : '' ?>" href="/admin/demandes?statut=tous">Tous</a>
            <a class="tab-pill <?= $statut === 'en_attente' ? 'active' : '' ?>" href="/admin/demandes?statut=en_attente">En attente</a>
            <a class="tab-pill <?= $statut === 'approuvee' ? 'active' : '' ?>" href="/admin/demandes?statut=approuvee">Approuvees</a>
            <a class="tab-pill <?= $statut === 'refusee' ? 'active' : '' ?>" href="/admin/demandes?statut=refusee">Refusees</a>
            <a class="tab-pill <?= $statut === 'annulee' ? 'active' : '' ?>" href="/admin/demandes?statut=annulee">Annulees</a>
        </div>
    </div>
    <table class="tbl">
        <thead>
            <tr>
                <th>Employe</th>
                <th>Departement</th>
                <th>Type</th>
                <th>Periode</th>
                <th>Duree</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($demandes) : ?>
                <?php foreach ($demandes as $demande) : ?>
                    <tr>
                        <td class="td-name"><?= esc($demande['prenom'] . ' ' . $demande['nom']) ?></td>
                        <td class="td-muted"><?= esc($demande['departement_nom'] ?? '-') ?></td>
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
                        <td>
                            <?php
                            $statutClass = match ($demande['statut']) {
                                'approuvee' => 's-approuvee',
                                'refusee' => 's-refusee',
                                'annulee' => 's-annulee',
                                default => 's-attente',
                            };
                            ?>
                            <span class="statut <?= esc($statutClass) ?>"><?= esc($demande['statut']) ?></span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="6">
                        <div class="empty">
                            <i class="bi bi-clipboard"></i>
                            <p>Aucune demande.</p>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
