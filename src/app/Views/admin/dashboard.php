<?= $this->extend('layout/app') ?>

<?= $this->section('topbar_actions') ?>
<a href="/admin/employes" class="btn-forest">Ajouter un employe</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="metrics">
    <div class="metric">
        <div class="metric-top"><div class="metric-icon mi-forest"><i class="bi bi-people"></i></div></div>
        <div class="metric-val"><?= esc($totalEmployes) ?></div>
        <div class="metric-label">Employes actifs</div>
    </div>
    <div class="metric">
        <div class="metric-top"><div class="metric-icon mi-amber"><i class="bi bi-hourglass-split"></i></div></div>
        <div class="metric-val"><?= esc($pendingDemandes) ?></div>
        <div class="metric-label">Demandes en attente</div>
    </div>
    <div class="metric">
        <div class="metric-top"><div class="metric-icon mi-green"><i class="bi bi-check-circle"></i></div></div>
        <div class="metric-val"><?= esc($approvedThisMonth) ?></div>
        <div class="metric-label">Approuvees ce mois</div>
    </div>
    <div class="metric">
        <div class="metric-top"><div class="metric-icon mi-blue"><i class="bi bi-diagram-3"></i></div></div>
        <div class="metric-val"><?= esc($totalDepartements) ?></div>
        <div class="metric-label">Departements</div>
    </div>
    <div class="metric">
        <div class="metric-top"><div class="metric-icon mi-red"><i class="bi bi-person-x"></i></div></div>
        <div class="metric-val"><?= esc(count($absentsToday)) ?></div>
        <div class="metric-label">Absents aujourd'hui</div>
    </div>
</div>

<div class="split-grid">
    <div class="data-card">
        <div class="data-card-head">
            <h3>Demandes recentes</h3>
            <a class="btn-secondary" href="/admin/demandes">Tout voir</a>
        </div>
        <table class="tbl">
            <thead>
                <tr>
                    <th>Employe</th>
                    <th>Type</th>
                    <th>Duree</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($recentDemandes) : ?>
                    <?php foreach ($recentDemandes as $demande) : ?>
                        <tr>
                            <td class="td-name"><?= esc($demande['prenom'] . ' ' . $demande['nom']) ?></td>
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
                        <td colspan="4">
                            <div class="empty">
                                <i class="bi bi-clipboard"></i>
                                <p>Aucune demande recente.</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="data-card">
        <div class="data-card-head">
            <h3>Absents aujourd'hui</h3>
        </div>
        <div class="content" style="padding:1rem 1.25rem">
            <?php if ($absentsToday) : ?>
                <div class="absent-list">
                    <?php foreach ($absentsToday as $absence) : ?>
                        <?php
                        $name = trim($absence['prenom'] . ' ' . $absence['nom']);
                        $initials = strtoupper(substr(trim((string) $absence['prenom']), 0, 1) . substr(trim((string) $absence['nom']), 0, 1));
                        $colors = ['av-green', 'av-blue', 'av-amber'];
                        $colorClass = $colors[abs(crc32($name)) % count($colors)];
                        $retour = $absence['date_fin'];
                        try {
                            $dt = new DateTime((string) $absence['date_fin']);
                            $retour = $dt->format('d/m');
                        } catch (Throwable $e) {
                        }
                        ?>
                        <div class="absent-item">
                            <div class="avatar <?= esc($colorClass) ?>"><?= esc($initials) ?></div>
                            <div class="absent-info">
                                <div class="absent-name"><?= esc($name) ?></div>
                                <div class="absent-sub"><?= esc($absence['libelle']) ?> · retour <?= esc($retour) ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <div class="empty" style="padding:1.25rem 0">
                    <i class="bi bi-person-x"></i>
                    <p>Aucun absent aujourd'hui.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if ($soldeCritiqueCount > 0) : ?>
    <div class="alert-card warn">
        <div>
            <?= esc($soldeCritiqueCount) ?> employe(s) ont un solde critique (&lt;= 2 jours).
        </div>
        <a href="/rh/soldes">Voir les soldes →</a>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>
