<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<div class="data-card">
    <div class="data-card-head">
        <h3>Soldes employes — <?= esc($annee) ?></h3>
    </div>
    <table class="tbl">
        <thead>
            <tr>
                <th>Employe</th>
                <th>Departement</th>
                <th>Type</th>
                <th>Attribues</th>
                <th>Pris</th>
                <th>Restant</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($soldes) : ?>
                <?php foreach ($soldes as $solde) : ?>
                    <tr>
                        <td class="td-name"><?= esc($solde['prenom'] . ' ' . $solde['nom']) ?></td>
                        <td class="td-muted"><?= esc($solde['departement_nom'] ?? '-') ?></td>
                        <td class="td-muted"><?= esc($solde['libelle']) ?></td>
                        <td class="td-mono"><?= esc($solde['jours_attribues']) ?></td>
                        <td class="td-mono"><?= esc($solde['jours_pris']) ?></td>
                        <td class="td-mono"><?= esc($solde['restant']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="6">
                        <div class="empty">
                            <i class="bi bi-archive"></i>
                            <p>Aucun solde disponible.</p>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
