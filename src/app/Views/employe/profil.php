<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<?php
$fullName = trim(($employe['prenom'] ?? '') . ' ' . ($employe['nom'] ?? ''));
$initials = strtoupper(substr(trim((string) ($employe['prenom'] ?? '')), 0, 1) . substr(trim((string) ($employe['nom'] ?? '')), 0, 1));
?>
<div class="data-card">
    <div class="data-card-head">
        <h3>Informations</h3>
    </div>
    <div class="content" style="padding:1rem 1.25rem">
        <div class="profile-row">
            <div class="avatar av-green"><?= esc($initials) ?></div>
            <div class="profile-info">
                <div class="pname"><?= esc($fullName !== '' ? $fullName : 'Employe') ?></div>
                <div class="pdept"><?= esc($employe['departement_nom'] ?? '-') ?></div>
            </div>
        </div>

        <div class="inline-stats">
            <div class="inline-stat"><i class="bi bi-envelope"></i> <strong><?= esc($employe['email'] ?? '-') ?></strong></div>
            <div class="inline-stat"><i class="bi bi-calendar2"></i> Embauche : <strong><?= esc($employe['date_embauche'] ?? '-') ?></strong></div>
            <div class="inline-stat"><i class="bi bi-shield-check"></i> Statut :
                <strong><?= (int) ($employe['actif'] ?? 0) === 1 ? 'actif' : 'inactif' ?></strong>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

