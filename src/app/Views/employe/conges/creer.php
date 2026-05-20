<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<?php
$soldeForOption = static function (array $type, array $soldeMap): ?int {
    $typeId = (int) $type['id'];
    $deductible = (int) ($type['deductible'] ?? 0);
    if ($deductible !== 1) {
        return null;
    }
    return (int) ($soldeMap[$typeId]['restant'] ?? 0);
};
?>
<div class="page-grid">
    <div class="form-section">
        <h3>Details de la demande</h3>
        <form method="post" action="/employe/conges/creer" id="conge-form">
            <?= csrf_field() ?>
            <div class="f-group">
                <label class="f-label">Type de conge *</label>
                <select class="f-select" name="type_conge_id" required>
                    <option value="">Selectionner</option>
                    <?php foreach ($types as $type) : ?>
                        <?php
                        $restantOpt = $soldeForOption($type, $soldeMap ?? []);
                        $label = $type['libelle'];
                        if ($restantOpt !== null) {
                            $label .= ' (' . $restantOpt . ' j restants)';
                        }
                        ?>
                        <option value="<?= esc($type['id']) ?>"><?= esc($label) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-grid-2">
                <div class="f-group">
                    <label class="f-label">Date de debut *</label>
                    <input type="date" class="f-input" name="date_debut" id="date-debut" required/>
                </div>
                <div class="f-group">
                    <label class="f-label">Date de fin *</label>
                    <input type="date" class="f-input" name="date_fin" id="date-fin" required/>
                </div>
            </div>

            <div class="f-computed" id="jours-box" style="display:none">
                <div class="f-computed-num" id="jours-nb">0</div>
                <div>
                    <div class="f-computed-label"><span id="jours-label">jours calendaires calcules</span></div>
                    <div class="f-hint" id="jours-range"></div>
                </div>
            </div>

            <div class="f-group">
                <label class="f-label">Motif (optionnel)</label>
                <textarea class="f-textarea" name="motif" placeholder="Precisez le motif de votre demande si necessaire..."></textarea>
                <div class="f-hint">Le motif est visible par le responsable RH.</div>
            </div>

            <div class="form-actions">
                <button class="btn-forest" type="submit">Soumettre la demande</button>
                <a class="btn-secondary" href="/employe/conges">Annuler</a>
            </div>
        </form>
    </div>

    <div class="side-stack">
        <div class="data-card">
            <div class="data-card-head">
                <h3>Vos soldes actuels</h3>
            </div>
            <div class="content" style="padding:1rem 1.25rem">
                <?php if (!empty($soldes)) : ?>
                    <div class="mini-solde-list">
                        <?php foreach ($soldes as $solde) : ?>
                            <?php
                            $attribues = (int) $solde['jours_attribues'];
                            $pris = (int) $solde['jours_pris'];
                            $restant = $attribues - $pris;
                            $percent = $attribues > 0 ? (int) round(($restant / $attribues) * 100) : 0;
                            $barClass = $percent <= 20 ? 'danger' : ($percent <= 40 ? 'warn' : '');
                            ?>
                            <div class="mini-solde">
                                <div class="mini-solde-top">
                                    <div class="mini-solde-name"><?= esc($solde['libelle']) ?></div>
                                    <div class="mini-solde-num"><?= esc($restant) ?> <span>j</span></div>
                                </div>
                                <div class="mini-solde-bar">
                                    <div class="mini-solde-fill <?= esc($barClass) ?>" style="width: <?= esc($percent) ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <div class="empty" style="padding:1rem 0">
                        <i class="bi bi-archive"></i>
                        <p>Aucun solde disponible.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="info-box info">
            Le solde est deduit uniquement a l'approbation de votre responsable.
        </div>

        <div class="data-card" style="background:rgba(255,255,255,.55)">
            <div class="data-card-head">
                <h3>Rappel des regles</h3>
            </div>
            <div class="content" style="padding:1rem 1.25rem">
                <ul class="rule-list">
                    <li>Preavis minimum : 48h avant la date de debut</li>
                    <li>Pas de chevauchement avec une demande en cours</li>
                    <li>Solde insuffisant = demande refusee automatiquement</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
