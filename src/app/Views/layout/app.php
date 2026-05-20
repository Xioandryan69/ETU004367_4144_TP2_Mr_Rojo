<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<title><?= esc($title ?? 'TechMada RH') ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet"/>
<link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet"/>
</head>
<body>
<?php
$role = session()->get('role');
$fullName = session()->get('employe_nom');
$employeId = (int) session()->get('employe_id');
$pendingCount = 0;
if ($role === 'employe' && $employeId > 0) {
    $pendingCount = model(\App\Models\CongeModel::class)
        ->where('employe_id', $employeId)
        ->where('statut', 'en_attente')
        ->countAllResults();
}
$pendingRh = 0;
if ($role === 'rh') {
    $pendingRh = model(\App\Models\CongeModel::class)
        ->where('statut', 'en_attente')
        ->countAllResults();
}
$statutParam = (string) service('request')->getGet('statut');
$uri = service('uri');
$path = '/' . trim($uri->getPath(), '/');
$active = static function (string $target) use ($path): string {
    if ($target === '/') {
        return $path === '/' ? 'active' : '';
    }
    return str_starts_with($path, $target) ? 'active' : '';
};
?>
<div class="app-wrap">
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-logo-icon"><i class="bi bi-briefcase"></i></div>
            <div class="sidebar-brand-name">TechMada RH<span>ADMINISTRATION</span></div>
        </div>
        
        <div class="sidebar-section">GESTION</div>
        <ul class="sidebar-nav">
            <?php if ($role === 'employe') : ?>
                <li><a href="/employe" class="<?= $active('/employe') ?>"><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
                <li><a href="/employe/conges/creer" class="<?= $active('/employe/conges/creer') ?>"><i class="bi bi-plus-circle"></i> Nouvelle demande</a></li>
                <li><a href="/employe/conges" class="<?= $active('/employe/conges') ?>"><i class="bi bi-calendar3"></i> Mes demandes
                    <?php if ($pendingCount > 0) : ?><span class="nav-badge alert"><?= esc($pendingCount) ?></span><?php endif; ?>
                </a></li>
                <li><a href="/employe/soldes" class="<?= $active('/employe/soldes') ?>"><i class="bi bi-archive"></i> Mes soldes</a></li>
                <li><a href="/employe/profil" class="<?= $active('/employe/profil') ?>"><i class="bi bi-person"></i> Mon profil</a></li>
            <?php elseif ($role === 'rh') : ?>
                <?php
                $rhIsHistorique = $path === '/rh' && $statutParam === 'tous';
                $rhIsInbox = $path === '/rh' && $statutParam !== 'tous';
                ?>
                <li><a href="/rh" class="<?= $rhIsInbox ? 'active' : '' ?>"><i class="bi bi-clipboard-check"></i> Demandes a traiter
                    <?php if ($pendingRh > 0) : ?><span class="nav-badge alert"><?= esc($pendingRh) ?></span><?php endif; ?>
                </a></li>
                <li><a href="/rh?statut=tous" class="<?= $rhIsHistorique ? 'active' : '' ?>"><i class="bi bi-clock-history"></i> Historique</a></li>
                <li><a href="/rh/soldes" class="<?= $active('/rh/soldes') ?>"><i class="bi bi-archive"></i> Soldes employes</a></li>
            <?php elseif ($role === 'admin') : ?>
                <?php
                $adminPending = model(\App\Models\CongeModel::class)->where('statut', 'en_attente')->countAllResults();
                ?>
                <li><a href="/admin" class="<?= $active('/admin') ?>"><i class="bi bi-grid-1x2"></i> Vue d'ensemble</a></li>
                <li><a href="/admin/demandes" class="<?= $active('/admin/demandes') ?>"><i class="bi bi-clipboard-check"></i> Toutes les demandes
                    <?php if ($adminPending > 0) : ?><span class="nav-badge alert"><?= esc($adminPending) ?></span><?php endif; ?>
                </a></li>
                <li><a href="/admin/employes" class="<?= $active('/admin/employes') ?>"><i class="bi bi-people"></i> Employes</a></li>
                <li><a href="/admin/departements" class="<?= $active('/admin/departements') ?>"><i class="bi bi-diagram-3"></i> Departements</a></li>
                <li><a href="/admin/types-conge" class="<?= $active('/admin/types-conge') ?>"><i class="bi bi-clipboard"></i> Types de conge</a></li>
                <li><a href="/rh/soldes" class="<?= $active('/rh/soldes') ?>"><i class="bi bi-archive"></i> Soldes annuels</a></li>
            <?php endif; ?>
        </ul>
        <div class="sidebar-user">
            <div class="s-user-row">
                <div class="avatar av-green"><?= esc(strtoupper(substr((string) $fullName, 0, 2))) ?></div>
                <div>
                    <div class="user-name"><?= esc($fullName ?? 'Utilisateur') ?></div>
                    <div class="user-role"><?= esc($role ?? 'role') ?></div>
                </div>
                <a href="/logout" style="margin-left:auto;color:rgba(255,255,255,.25);font-size:1.1rem" title="Deconnexion"><i class="bi bi-box-arrow-right"></i></a>
            </div>
        </div>
    </aside>

    <div class="main">
        <div class="topbar">
            <div>
                <div class="topbar-title"><?= esc($title ?? '') ?></div>
                <div class="topbar-breadcrumb"><?= esc($breadcrumb ?? '') ?></div>
            </div>
            <div class="topbar-actions">
                <?= $this->renderSection('topbar_actions') ?>
                <?php if ($role === 'employe') : ?>
                    <a href="/employe/conges/creer" class="btn-forest" style="padding:7px 14px;font-size:.82rem">
                        <i class="bi bi-plus-lg"></i> Nouvelle demande
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="content">
            <?= $this->include('partials/flash') ?>
            <?= $this->renderSection('content') ?>
        </div>
        <div class="footer-app">TechMada RH <span>CI4</span> · 2026</div>
    </div>
</div>
<script src="<?= base_url('assets/js/app.js') ?>" defer></script>
</body>
</html>
