<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<?php
$fmtLong = static function (?string $date): string {
    if (!$date) {
        return '-';
    }
    $months = [
        1 => 'janv.',
        2 => 'fevr.',
        3 => 'mars',
        4 => 'avr.',
        5 => 'mai',
        6 => 'juin',
        7 => 'juil.',
        8 => 'aout',
        9 => 'sept.',
        10 => 'oct.',
        11 => 'nov.',
        12 => 'dec.',
    ];
    try {
        $dt = new DateTime($date);
        $m = (int) $dt->format('n');
        return (int) $dt->format('j') . ' ' . ($months[$m] ?? $dt->format('m')) . ' ' . $dt->format('Y');
    } catch (Throwable $e) {
        return $date;
    }
};
?>
<div class="data-card">
    <div class="data-card-head">
        <h3>Toutes mes demandes</h3>
        <form method="get" action="/employe/conges" class="form-inline">
            <select class="f-select" name="statut" onchange="this.form.submit()">
                <option value="tous" <?= ($statut ?? 'tous') === 'tous' ? 'selected' : '' ?>>Tous les statuts</option>
                <option value="en_attente" <?= ($statut ?? 'tous') === 'en_attente' ? 'selected' : '' ?>>En attente</option>
                <option value="approuvee" <?= ($statut ?? 'tous') === 'approuvee' ? 'selected' : '' ?>>Approuvee</option>
                <option value="refusee" <?= ($statut ?? 'tous') === 'refusee' ? 'selected' : '' ?>>Refusee</option>
                <option value="annulee" <?= ($statut ?? 'tous') === 'annulee' ? 'selected' : '' ?>>Annulee</option>
            </select>
        </form>
    </div>
    <table class="tbl">
        <thead>
            <tr>
                <th>Type</th>
                <th>Debut</th>
                <th>Fin</th>
                <th>Duree</th>
                <th>Statut</th>
                <th>Commentaire RH</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($conges) : ?>
                <?php foreach ($conges as $conge) : ?>
                    <tr>
                        <td class="td-name">
                            <?php
                            $typeLabel = strtolower($conge['libelle']);
                            $typeClass = match ($typeLabel) {
                                'conge annuel' => 't-annuel',
                                'maladie' => 't-maladie',
                                'formation' => 't-special',
                                default => 't-special',
                            };
                            ?>
                            <span class="type-badge <?= esc($typeClass) ?>"><?= esc($conge['libelle']) ?></span>
                        </td>
                        <td class="td-mono"><?= esc($fmtLong($conge['date_debut'] ?? null)) ?></td>
                        <td class="td-mono"><?= esc($fmtLong($conge['date_fin'] ?? null)) ?></td>
                        <td class="td-mono"><?= esc($conge['nb_jours']) ?> j</td>
                        <td>
                            <?php
                            $statutClass = match ($conge['statut']) {
                                'approuvee' => 's-approuvee',
                                'refusee' => 's-refusee',
                                'annulee' => 's-annulee',
                                default => 's-attente',
                            };
                            ?>
                            <span class="statut <?= esc($statutClass) ?>"><?= esc($conge['statut']) ?></span>
                        </td>
                        <td class="td-muted"><?= esc($conge['commentaire_rh'] ?? '-') ?></td>
                        <td>
                            <?php if ($conge['statut'] === 'en_attente') : ?>
                                <form method="post" action="/employe/conges/<?= esc($conge['id']) ?>/annuler">
                                    <?= csrf_field() ?>
                                    <button class="btn-sm btn-cancel" type="submit"><i class="bi bi-x-lg"></i> Annuler</button>
                                </form>
                            <?php else : ?>
                                <span class="td-muted">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="7">
                        <div class="empty">
                            <i class="bi bi-calendar3"></i>
                            <p>Aucune demande pour le moment.</p>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
