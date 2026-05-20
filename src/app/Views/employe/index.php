<?php
$session = session();
$conges = $conges ?? [];
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
<title>TechMada RH — Mes demandes</title>
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
.sidebar-nav{list-style:none;padding:0 .75rem;margin:1rem 0 0}
.sidebar-nav li{margin-bottom:2px}
.sidebar-nav li a{display:flex;align-items:center;gap:9px;padding:9px 11px;border-radius:7px;color:rgba(255,255,255,.55);text-decoration:none;font-size:.85rem;font-weight:400;transition:all .15s}
.sidebar-nav li a:hover{background:rgba(255,255,255,.06);color:rgba(255,255,255,.9)}
.sidebar-nav li a.active{background:var(--forest);color:var(--white)}
.sidebar-user{padding:.85rem .75rem;border-top:1px solid rgba(255,255,255,.06);margin-top:auto}
.s-user-row{display:flex;align-items:center;gap:9px;padding:9px 11px;border-radius:7px;cursor:pointer;transition:background .15s}
.s-user-row:hover{background:rgba(255,255,255,.06)}
.avatar{width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:500;color:var(--white);flex-shrink:0;font-family:'DM Mono',monospace}
.av-green{background:var(--forest2)}
.user-name{font-size:.825rem;font-weight:500;color:var(--white);line-height:1.2}
.user-role{font-size:.65rem;color:rgba(255,255,255,.35);text-transform:uppercase;letter-spacing:.06em}
.main{flex:1;min-width:0;display:flex;flex-direction:column}
.topbar{height:var(--topbar-h);background:var(--white);border-bottom:1px solid var(--border);display:flex;align-items:center;padding:0 1.75rem;gap:1rem;position:sticky;top:0;z-index:10}
.topbar-title{font-family:'Playfair Display',serif;font-size:1.05rem;font-weight:600;color:var(--ink)}
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
.statut{display:inline-flex;align-items:center;gap:5px;font-size:.7rem;font-weight:500;padding:4px 9px;border-radius:12px}
.statut::before{content:'';width:5px;height:5px;border-radius:50%;display:inline-block;flex-shrink:0}
.s-attente{background:var(--warn-bg);color:var(--warn)}
.s-attente::before{background:var(--warn)}
.s-approuvee{background:var(--success-bg);color:var(--success)}
.s-approuvee::before{background:var(--success)}
.s-refusee{background:var(--danger-bg);color:var(--danger)}
.s-refusee::before{background:var(--danger)}
.s-annulee{background:#f1efe8;color:#7a8f80}
.s-annulee::before{background:#b4b2a9}
.type-badge{display:inline-block;font-size:.68rem;font-weight:500;padding:3px 8px;border-radius:4px}
.t-annuel{background:var(--mint);color:var(--forest)}
.t-maladie{background:var(--info-bg);color:var(--info)}
.t-special{background:#f0e8fb;color:#5a2d82}
.t-sans-solde{background:#f1efe8;color:#7a8f80}
.action-btns{display:flex;gap:5px;flex-wrap:wrap}
.btn-sm{font-size:.72rem;font-weight:500;padding:5px 10px;border-radius:6px;border:1px solid transparent;cursor:pointer;transition:all .15s;text-decoration:none;display:inline-flex;align-items:center;gap:4px;font-family:'DM Sans',sans-serif}
.btn-cancel{background:var(--cream);color:var(--muted);border-color:var(--border)}
.btn-cancel:hover{background:var(--danger-bg);color:var(--danger)}
.btn-forest{background:var(--forest);color:var(--white);border:none;border-radius:8px;padding:7px 14px;font-size:.82rem;font-weight:500;cursor:pointer;font-family:'DM Sans',sans-serif;display:inline-flex;align-items:center;gap:6px;text-decoration:none;transition:background .15s}
.btn-forest:hover{background:var(--forest2);color:var(--white)}
.footer-app{padding:.75rem 1.75rem;border-top:1px solid var(--border);font-size:.75rem;color:var(--muted);background:var(--white);display:flex;align-items:center;gap:6px}
.footer-app span{color:var(--forest);font-weight:500}
</style>
</head>
<body>
<div class="app-wrap">
  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="sidebar-logo-icon"><i class="bi bi-briefcase"></i></div>
      <div class="sidebar-brand-name">TechMada RH<span>Espace employé</span></div>
    </div>
    <ul class="sidebar-nav">
      <li><a href="<?= site_url('employe/dashboard') ?>"><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
      <li><a href="<?= site_url('employe/nouveau-conge') ?>"><i class="bi bi-plus-circle"></i> Nouvelle demande</a></li>
      <li><a href="<?= site_url('employe/mes-conges') ?>" class="active"><i class="bi bi-calendar3"></i> Mes demandes</a></li>
      <li><a href="<?= site_url('employe/profil') ?>"><i class="bi bi-person"></i> Mon profil</a></li>
      <li><a href="<?= site_url('employe/calendar') ?>"><i class="bi bi-person"></i> Calendrier</a></li>
      <li><a href="<?= site_url('employe/chart') ?>"><i class="bi bi-person"></i> Chart</a></li>
    </ul>
    <div class="sidebar-user">
      <div class="s-user-row">
        <div class="avatar av-green"><?= substr((string) $session->get('nom'), 0, 1) ?></div>
        <div>
          <div class="user-name"><?= esc((string) $session->get('nom')) ?></div>
          <div class="user-role"><?= esc((string) $session->get('role')) ?></div>
        </div>
      </div>
    </div>
  </aside>

  <div class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">Mes demandes de congé</div>
      </div>
      <div class="topbar-actions">
        <a href="<?= site_url('employe/nouveau-conge') ?>" class="btn-forest"><i class="bi bi-plus-lg"></i> Nouvelle demande</a>
      </div>
    </div>

    <div class="content">
      <?php if ($successMessage): ?>
        <div class="flash flash-success"><i class="bi bi-check-circle-fill"></i><?= esc($successMessage) ?></div>
      <?php endif; ?>
      <?php if ($errorMessage): ?>
        <div class="flash flash-error"><i class="bi bi-exclamation-circle-fill"></i><?= esc($errorMessage) ?></div>
      <?php endif; ?>

      <div class="data-card">
        <div class="data-card-head">
          <h3>Toutes mes demandes</h3>
        </div>
        <table class="tbl">
          <thead>
            <tr><th>Type</th><th>Début</th><th>Fin</th><th>Durée</th><th>Statut</th><th>Commentaire RH</th><th>Action</th></tr>
          </thead>
          <tbody>
            <?php if (empty($conges)): ?>
              <tr><td colspan="7" class="td-muted" style="padding:2rem;text-align:center">Aucune demande enregistrée.</td></tr>
            <?php endif; ?>
            <?php foreach ($conges as $conge):
              $statut = $conge['statut'];
              $badgeClass = $statut === 'approuvee' ? 's-approuvee' : ($statut === 'refusee' ? 's-refusee' : ($statut === 'annulee' ? 's-annulee' : 's-attente'));
              $typeLabel = strtolower((string) ($conge['type_conge'] ?? ''));
              $typeClass = 't-annuel';
              if (str_contains($typeLabel, 'maladie')) $typeClass = 't-maladie';
              elseif (str_contains($typeLabel, 'spécial') || str_contains($typeLabel, 'special')) $typeClass = 't-special';
              elseif (str_contains($typeLabel, 'solde')) $typeClass = 't-sans-solde';
            ?>
              <tr>
                <td><span class="type-badge <?= $typeClass ?>"><?= esc((string) ($conge['type_conge'] ?? '')) ?></span></td>
                <td class="td-muted"><?= date('d/m/Y', strtotime($conge['date_debut'])) ?></td>
                <td class="td-muted"><?= date('d/m/Y', strtotime($conge['date_fin'])) ?></td>
                <td class="td-mono"><?= (int) $conge['nb_jours'] ?> j</td>
                <td><span class="statut <?= $badgeClass ?>"><?= esc((string) $statut) ?></span></td>
                <td class="td-muted" style="font-size:.78rem"><?= esc((string) ($conge['commentaire_rh'] ?? '—')) ?></td>
                <td>
                  <?php if ($statut === 'en_attente'): ?>
                    <form action="<?= site_url('employe/conges/annuler/' . $conge['id']) ?>" method="post" style="display:inline">
                      <?= csrf_field() ?>
                      <button class="btn-sm btn-cancel" type="submit"><i class="bi bi-x"></i> Annuler</button>
                    </form>
                  <?php else: ?>
                    <span class="td-muted" style="font-size:.75rem">—</span>
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
