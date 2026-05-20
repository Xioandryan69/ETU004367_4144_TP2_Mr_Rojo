<?php
$session = session();
$demandes = $demandes ?? [];
$departements = $departements ?? [];
$statutFiltre = $statutFiltre ?? '';
$departementFiltre = $departementFiltre ?? '';
$pendingCount = $pendingCount ?? 0;
$success = session('success');
$error = session('error');
$successMessage = is_string($success) ? $success : '';
$errorMessage = is_string($error) ? $error : '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<title>TechMada RH — Demandes RH</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet"/>
<style>
:root{--ink:#1c2b1e;--forest:#2d5a3d;--forest2:#3d7a52;--leaf:#5fa876;--mint:#d4ede0;--cream:#f8f6f1;--white:#ffffff;--border:#dde8e1;--muted:#7a8f80;--danger:#c0392b;--danger-bg:#fdf0ee;--danger-br:#f0b8b2;--warn:#b8750a;--warn-bg:#fef9ee;--warn-br:#f5d98a;--success:#1e6b3f;--success-bg:#edf7f2;--success-br:#8fd4aa;--info:#1a4f7a;--info-bg:#eaf2fb;--info-br:#8fbde8;--sidebar-w:240px;--topbar-h:62px}
*{box-sizing:border-box}
body{font-family:'DM Sans',sans-serif;background:var(--cream);color:var(--ink);margin:0;font-size:15px}
h1,h2,h3,.brand-name{font-family:'Playfair Display',serif}
.app-wrap{display:flex;min-height:100vh}
.sidebar{width:var(--sidebar-w);background:var(--ink);display:flex;flex-direction:column;flex-shrink:0;position:sticky;top:0;height:100vh;overflow-y:auto}
.sidebar-brand{padding:1.4rem 1.2rem 1rem;display:flex;align-items:center;gap:10px;border-bottom:1px solid rgba(255,255,255,.06)}
.sidebar-logo-icon{width:34px;height:34px;background:var(--forest);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.sidebar-logo-icon i{color:var(--white);font-size:1.1rem}
.sidebar-brand-name{font-family:'Playfair Display',serif;font-size:1rem;color:var(--white);line-height:1.2}
.sidebar-brand-name span{display:block;font-size:.65rem;font-family:'DM Sans',sans-serif;font-weight:400;color:rgba(255,255,255,.35);letter-spacing:.05em;text-transform:uppercase}
.sidebar-section{padding:.75rem 1.1rem .3rem;font-size:.62rem;font-weight:500;letter-spacing:1.4px;text-transform:uppercase;color:rgba(255,255,255,.25);margin-top:.25rem}
.sidebar-nav{list-style:none;padding:0 .75rem;margin:0}
.sidebar-nav li{margin-bottom:2px}
.sidebar-nav li a{display:flex;align-items:center;gap:9px;padding:9px 11px;border-radius:7px;color:rgba(255,255,255,.55);text-decoration:none;font-size:.85rem;font-weight:400;transition:all .15s}
.sidebar-nav li a:hover{background:rgba(255,255,255,.06);color:rgba(255,255,255,.9)}
.sidebar-nav li a.active{background:var(--forest);color:var(--white)}
.sidebar-nav li a i{font-size:1.05rem;flex-shrink:0}
.nav-badge{margin-left:auto;font-size:.65rem;padding:2px 7px;border-radius:10px;background:rgba(255,255,255,.12);color:var(--white)}
.nav-badge.alert{background:var(--danger);color:var(--white)}
.sidebar-user{padding:.85rem .75rem;border-top:1px solid rgba(255,255,255,.06);margin-top:auto}
.s-user-row{display:flex;align-items:center;gap:9px;padding:9px 11px;border-radius:7px;cursor:pointer;transition:background .15s}
.s-user-row:hover{background:rgba(255,255,255,.06)}
.avatar{width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:500;color:var(--white);flex-shrink:0;font-family:'DM Mono',monospace}
.av-blue{background:#1a4f7a}
.user-name{font-size:.825rem;font-weight:500;color:var(--white);line-height:1.2}
.user-role{font-size:.65rem;color:rgba(255,255,255,.35);text-transform:uppercase;letter-spacing:.06em}
.main{flex:1;min-width:0;display:flex;flex-direction:column}
.topbar{height:var(--topbar-h);background:var(--white);border-bottom:1px solid var(--border);display:flex;align-items:center;padding:0 1.75rem;gap:1rem;position:sticky;top:0;z-index:10}
.topbar-title{font-family:'Playfair Display',serif;font-size:1.05rem;font-weight:600;color:var(--ink)}
.topbar-breadcrumb{font-size:.78rem;color:var(--muted);display:flex;align-items:center;gap:5px}
.topbar-breadcrumb a{color:var(--muted);text-decoration:none}
.topbar-breadcrumb a:hover{color:var(--forest)}
.topbar-actions{margin-left:auto;display:flex;align-items:center;gap:8px}
.content{padding:1.75rem;flex:1}
.flash{padding:11px 14px;border-radius:8px;font-size:.85rem;font-weight:500;display:flex;align-items:center;gap:9px;margin-bottom:1.25rem;border:1px solid transparent}
.flash-success{background:var(--success-bg);color:var(--success);border-color:var(--success-br)}
.flash-error{background:var(--danger-bg);color:var(--danger);border-color:var(--danger-br)}
.data-card{background:var(--white);border:1px solid var(--border);border-radius:12px;overflow:hidden;margin-bottom:1.5rem}
.data-card-head{padding:.9rem 1.25rem;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:.75rem;flex-wrap:wrap}
.tbl{width:100%;border-collapse:collapse;font-size:.85rem}
.tbl thead th{padding:9px 14px;font-size:.68rem;font-weight:500;text-transform:uppercase;letter-spacing:.07em;color:var(--muted);background:var(--cream);border-bottom:1px solid var(--border);text-align:left;white-space:nowrap}
.tbl tbody tr{border-bottom:1px solid var(--border);transition:background .1s}
.tbl tbody tr:last-child{border-bottom:none}
.tbl tbody tr:hover{background:var(--cream)}
.tbl td{padding:12px 14px;color:var(--ink);vertical-align:middle}
.td-muted{color:var(--muted)}
.td-mono{font-family:'DM Mono',monospace;font-size:.8rem}
.profile-row{display:flex;align-items:center;gap:12px}
.profile-row .avatar{width:32px;height:32px;font-size:.7rem}
.profile-info .pname{font-weight:500;font-size:.9rem;color:var(--ink)}
.profile-info .pdept{font-size:.75rem;color:var(--muted)}
.statut{display:inline-flex;align-items:center;gap:5px;font-size:.7rem;font-weight:500;padding:4px 9px;border-radius:12px}
.statut::before{content:'';width:5px;height:5px;border-radius:50%;display:inline-block;flex-shrink:0}
.s-attente{background:var(--warn-bg);color:var(--warn)}
.s-attente::before{background:var(--warn)}
.s-approuvee{background:var(--success-bg);color:var(--success)}
.s-approuvee::before{background:var(--success)}
.s-refusee{background:var(--danger-bg);color:var(--danger)}
.s-refusee::before{background:var(--danger)}
.action-btns{display:flex;gap:5px;flex-wrap:wrap}
.btn-sm{font-size:.72rem;font-weight:500;padding:5px 10px;border-radius:6px;border:1px solid transparent;cursor:pointer;transition:all .15s;text-decoration:none;display:inline-flex;align-items:center;gap:4px;font-family:'DM Sans',sans-serif}
.btn-approve{background:var(--success-bg);color:var(--success);border-color:var(--success-br)}
.btn-approve:hover{background:#d5f0e3}
.btn-refuse{background:var(--danger-bg);color:var(--danger);border-color:var(--danger-br)}
.btn-refuse:hover{background:#f8dbd8}
.f-select{width:auto;border:1.5px solid var(--border);border-radius:8px;padding:6px 10px;font-size:.8rem;font-family:'DM Sans',sans-serif;background:var(--white);color:var(--ink)}
.f-textarea{width:100%;border:1.5px solid var(--border);border-radius:8px;padding:10px 12px;font-size:.875rem;font-family:'DM Sans',sans-serif;background:var(--white);color:var(--ink);resize:vertical;min-height:70px}
.footer-app{padding:.75rem 1.75rem;border-top:1px solid var(--border);font-size:.75rem;color:var(--muted);background:var(--white);display:flex;align-items:center;gap:6px}
.footer-app span{color:var(--forest);font-weight:500}
</style>
</head>
<body>
<div class="app-wrap">
  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="sidebar-logo-icon"><i class="bi bi-person-check"></i></div>
      <div class="sidebar-brand-name">TechMada RH<span>Espace responsable</span></div>
    </div>
    <div class="sidebar-section">Menu</div>
    <ul class="sidebar-nav">
      <li><a href="<?= site_url('rh') ?>" class="active"><i class="bi bi-inbox"></i> Demandes à traiter <span class="nav-badge alert"><?= (int) $pendingCount ?></span></a></li>
    </ul>
    <div class="sidebar-user">
      <div class="s-user-row">
        <div class="avatar av-blue"><?= substr((string) $session->get('nom'), 0, 1) ?></div>
        <div>
          <div class="user-name"><?= esc((string) $session->get('nom')) ?></div>
          <div class="user-role"><?= esc((string) $session->get('role')) ?></div>
        </div>
        <a href="<?= site_url('logout') ?>" style="margin-left:auto;color:rgba(255,255,255,.25);font-size:1.1rem"><i class="bi bi-box-arrow-right"></i></a>
      </div>
    </div>
  </aside>

  <div class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">Demandes à traiter</div>
        <div class="topbar-breadcrumb">Accueil</div>
      </div>
      <div class="topbar-actions">
        <span style="font-size:.8rem;color:var(--muted);background:var(--warn-bg);border:1px solid var(--warn-br);border-radius:6px;padding:5px 10px;display:flex;align-items:center;gap:5px;color:var(--warn)">
          <i class="bi bi-hourglass-split"></i> <?= (int) $pendingCount ?> en attente
        </span>
      </div>
    </div>

    <div class="content">
      <?php if ($successMessage): ?>
        <div class="flash flash-success"><i class="bi bi-check-circle-fill"></i><?= esc($successMessage) ?></div>
      <?php endif; ?>
      <?php if ($errorMessage): ?>
        <div class="flash flash-error"><i class="bi bi-exclamation-circle-fill"></i><?= esc($errorMessage) ?></div>
      <?php endif; ?>

      <form method="get" style="display:flex;gap:8px;margin-bottom:1.25rem;flex-wrap:wrap">
        <select class="f-select" name="statut">
          <option value="">Tous les statuts</option>
          <option value="en_attente" <?= $statutFiltre === 'en_attente' ? 'selected' : '' ?>>En attente</option>
          <option value="approuvee" <?= $statutFiltre === 'approuvee' ? 'selected' : '' ?>>Approuvées</option>
          <option value="refusee" <?= $statutFiltre === 'refusee' ? 'selected' : '' ?>>Refusées</option>
        </select>
        <select class="f-select" name="departement_id">
          <option value="">Tous les départements</option>
          <?php foreach ($departements as $departement): ?>
            <option value="<?= (int) $departement['id'] ?>" <?= $departementFiltre == $departement['id'] ? 'selected' : '' ?>>
              <?= esc((string) $departement['nom']) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <button class="btn-sm btn-approve" type="submit">Filtrer</button>
      </form>

      <div class="data-card">
        <div class="data-card-head"><h3>Toutes les demandes</h3></div>
        <table class="tbl">
          <thead>
            <tr><th>Employé</th><th>Type</th><th>Période</th><th>Durée</th><th>Statut</th><th>Actions</th></tr>
          </thead>
          <tbody>
            <?php if (empty($demandes)): ?>
              <tr><td colspan="6" class="td-muted" style="text-align:center;padding:2rem">Aucune demande trouvée.</td></tr>
            <?php endif; ?>
            <?php foreach ($demandes as $demande):
              $initials = substr((string) $demande['prenom'], 0, 1) . substr((string) $demande['nom'], 0, 1);
              $statut = (string) $demande['statut'];
              $badgeClass = $statut === 'approuvee' ? 's-approuvee' : ($statut === 'refusee' ? 's-refusee' : 's-attente');
            ?>
              <tr>
                <td>
                  <div class="profile-row">
                    <div class="avatar av-blue"><?= esc(strtoupper($initials)) ?></div>
                    <div class="profile-info">
                      <div class="pname"><?= esc((string) $demande['prenom']) ?> <?= esc((string) $demande['nom']) ?></div>
                      <div class="pdept"><?= esc((string) ($demande['departement'] ?? '')) ?></div>
                    </div>
                  </div>
                </td>
                <td><?= esc((string) $demande['type_conge']) ?></td>
                <td class="td-muted"><?= date('d/m/Y', strtotime($demande['date_debut'])) ?> → <?= date('d/m/Y', strtotime($demande['date_fin'])) ?></td>
                <td class="td-mono"><?= (int) $demande['nb_jours'] ?> j</td>
                <td><span class="statut <?= $badgeClass ?>"><?= esc($statut) ?></span></td>
                <td>
                  <?php if ($statut === 'en_attente'): ?>
                    <div class="action-btns">
                      <form action="<?= site_url('rh/approve/' . $demande['id']) ?>" method="post">
                        <?= csrf_field() ?>
                        <button class="btn-sm btn-approve" type="submit"><i class="bi bi-check-lg"></i> Approuver</button>
                      </form>
                      <form action="<?= site_url('rh/refuse/' . $demande['id']) ?>" method="post">
                        <?= csrf_field() ?>
                        <textarea name="commentaire_rh" class="f-textarea" placeholder="Commentaire (optionnel)"></textarea>
                        <button class="btn-sm btn-refuse" type="submit" style="margin-top:6px"><i class="bi bi-x-lg"></i> Refuser</button>
                      </form>
                    </div>
                  <?php else: ?>
                    <span class="td-muted" style="font-size:.75rem">Traité</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
  </div>
</div>
</body>
</html>
