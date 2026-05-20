<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<div class="metrics">
    <div class="metric">
        <div class="metric-top"><div class="metric-icon mi-amber"><i class="bi bi-hourglass-split"></i></div></div>
        <div class="metric-val"><?= esc($pending) ?></div>
        <div class="metric-label">En attente</div>
    </div>
    <div class="metric">
        <div class="metric-top"><div class="metric-icon mi-green"><i class="bi bi-check-circle"></i></div></div>
        <div class="metric-val"><?= esc($approved) ?></div>
        <div class="metric-label">Approuvees</div>
    </div>
    <div class="metric">
        <div class="metric-top"><div class="metric-icon mi-forest"><i class="bi bi-calendar-check"></i></div></div>
        <div class="metric-val"><?= esc($restant) ?></div>
        <div class="metric-label">Jours restants</div>
        <div class="metric-sub">sur <?= esc($totalAttribues) ?> cette annee</div>
    </div>
    <div class="metric">
        <div class="metric-top"><div class="metric-icon mi-red"><i class="bi bi-x-circle"></i></div></div>
        <div class="metric-val"><?= esc($refused) ?></div>
        <div class="metric-label">Refusees</div>
    </div>
</div>

<div class="data-card">
    <div class="data-card-head">
        <h3>Mes soldes de conges — <?= esc($annee) ?></h3>
    </div>
    <div class="content" style="padding:1rem 1.25rem">
        <?php if ($soldes) : ?>
            <div class="solde-grid">
            <?php foreach ($soldes as $solde) : ?>
                <?php
                $attribues = (int) $solde['jours_attribues'];
                $pris = (int) $solde['jours_pris'];
                $restant = $attribues - $pris;
                $percent = $attribues > 0 ? (int) round(($restant / $attribues) * 100) : 0;
                $barClass = $percent <= 20 ? 'danger' : ($percent <= 40 ? 'warn' : '');
                ?>
                <div class="solde-card">
                    <div class="solde-header">
                        <div class="solde-type"><?= esc($solde['libelle']) ?></div>
                        <div class="solde-nums"><strong><?= esc($restant) ?></strong> / <?= esc($attribues) ?> j</div>
                    </div>
                    <div class="solde-bar">
                        <div class="solde-fill <?= esc($barClass) ?>" style="width: <?= esc($percent) ?>%"></div>
                    </div>
                    <div class="solde-label"><?= esc($restant) ?> jours restants · <?= esc($pris) ?> pris</div>
                </div>
            <?php endforeach; ?>
            </div>
        <?php else : ?>
            <div class="empty">
                <i class="bi bi-archive"></i>
                <p>Aucun solde disponible.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="data-card">
    <div class="data-card-head">
        <h3>Mes dernieres demandes</h3>
        <a class="btn-secondary" href="/employe/conges">Voir tout</a>
    </div>
    <table class="tbl">
        <thead>
            <tr>
                <th>Type</th>
                <th>Du</th>
                <th>Au</th>
                <th>Duree</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($recentDemandes) : ?>
                <?php foreach ($recentDemandes as $demande) : ?>
                    <tr>
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
                        <td class="td-mono"><?= esc($demande['date_debut']) ?></td>
                        <td class="td-mono"><?= esc($demande['date_fin']) ?></td>
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
                    <td colspan="5">
                        <div class="empty">
                            <i class="bi bi-calendar3"></i>
                            <p>Aucune demande recente.</p>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
