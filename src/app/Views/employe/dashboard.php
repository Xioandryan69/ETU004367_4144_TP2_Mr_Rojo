<?php
$session = session();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<title>TechMada RH — Espace Employé</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet"/>
<style>
/* ═══════════════════════════════════════════
   TOKENS
═══════════════════════════════════════════ */
:root{
  --ink:      #1c2b1e;
  --forest:   #2d5a3d;
  --forest2:  #3d7a52;
  --leaf:     #5fa876;
  --mint:     #d4ede0;
  --cream:    #f8f6f1;
  --white:    #ffffff;
  --border:   #dde8e1;
  --muted:    #7a8f80;
  --danger:   #c0392b;
  --danger-bg:#fdf0ee;
  --danger-br:#f0b8b2;
  --warn:     #b8750a;
  --warn-bg:  #fef9ee;
  --warn-br:  #f5d98a;
  --success:  #1e6b3f;
  --success-bg:#edf7f2;
  --success-br:#8fd4aa;
  --info:     #1a4f7a;
  --info-bg:  #eaf2fb;
  --info-br:  #8fbde8;
  --sidebar-w:240px;
  --topbar-h: 62px;
}
*{box-sizing:border-box}
body{font-family:'DM Sans',sans-serif;background:var(--cream);color:var(--ink);margin:0;font-size:15px}
h1,h2,h3,.brand-name{font-family:'Playfair Display',serif}
code,pre,.mono{font-family:'DM Mono',monospace}

/* ─── MOTIF GÉOMÉTRIQUE ────────────────── */
.geo-bg{
  position:relative;
  overflow:hidden;
}
.geo-bg::before{
  content:'';
  position:absolute;inset:0;
  background-image:
    repeating-linear-gradient(0deg,transparent,transparent 39px,rgba(45,90,61,.04) 40px),
    repeating-linear-gradient(90deg,transparent,transparent 39px,rgba(45,90,61,.04) 40px);
  pointer-events:none;
  z-index:0;
}
.geo-bg>*{position:relative;z-index:1}

/* ─── LAYOUT APP ────────────────────────── */
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
.sidebar-user{padding:.85rem .75rem;border-top:1px solid rgba(255,255,255,.06);margin-top:auto}
.s-user-row{display:flex;align-items:center;gap:9px;padding:9px 11px;border-radius:7px;cursor:pointer;transition:background .15s}
.s-user-row:hover{background:rgba(255,255,255,.06)}
.avatar{width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:500;color:var(--white);flex-shrink:0;font-family:'DM Mono',monospace}
.av-green{background:var(--forest2)}
.user-name{font-size:.825rem;font-weight:500;color:var(--white);line-height:1.2}
.user-role{font-size:.65rem;color:rgba(255,255,255,.35);text-transform:uppercase;letter-spacing:.06em}

/* ─── MAIN ──────────────────────────────── */
.main{flex:1;min-width:0;display:flex;flex-direction:column}
.topbar{height:var(--topbar-h);background:var(--white);border-bottom:1px solid var(--border);display:flex;align-items:center;padding:0 1.75rem;gap:1rem;position:sticky;top:0;z-index:10}
.topbar-title{font-family:'Playfair Display',serif;font-size:1.05rem;font-weight:600;color:var(--ink)}
.topbar-actions{margin-left:auto;display:flex;align-items:center;gap:8px}
.icon-btn{width:34px;height:34px;border:1.5px solid var(--border);background:var(--white);border-radius:7px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--muted);transition:all .15s;text-decoration:none}
.icon-btn:hover{border-color:var(--forest);color:var(--forest)}
.content{padding:1.75rem;flex:1}

/* ─── SOLDE BAR (spécial congés) ─────────── */
.solde-card{background:var(--white);border:1px solid var(--border);border-radius:12px;padding:1.1rem 1.25rem;margin-bottom:1rem}
.solde-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:.6rem}
.solde-type{font-size:.875rem;font-weight:500;color:var(--ink)}
.solde-nums{font-family:'DM Mono',monospace;font-size:.8rem;color:var(--muted)}
.solde-nums strong{color:var(--ink)}
.solde-bar{height:6px;background:var(--mint);border-radius:3px;overflow:hidden}
.solde-fill{height:100%;background:var(--forest2);border-radius:3px;transition:width .3s}
.solde-fill.warn{background:var(--warn)}
.solde-fill.danger{background:var(--danger)}
.solde-label{font-size:.72rem;color:var(--muted);margin-top:4px}

/* ─── DATA CARD + TABLE ─────────────────── */
.data-card{background:var(--white);border:1px solid var(--border);border-radius:12px;overflow:hidden;margin-bottom:1.5rem}
.data-card-head{padding:.9rem 1.25rem;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:.75rem;flex-wrap:wrap}
.data-card-head h3{font-family:'Playfair Display',serif;font-size:.95rem;margin:0;font-weight:600;color:var(--ink)}
.tbl{width:100%;border-collapse:collapse;font-size:.85rem}
.tbl thead th{padding:9px 14px;font-size:.68rem;font-weight:500;text-transform:uppercase;letter-spacing:.07em;color:var(--muted);background:var(--cream);border-bottom:1px solid var(--border);text-align:left;white-space:nowrap}
.tbl tbody tr{border-bottom:1px solid var(--border);transition:background .1s}
.tbl tbody tr:last-child{border-bottom:none}
.tbl tbody tr:hover{background:var(--cream)}
.tbl td{padding:12px 14px;color:var(--ink);vertical-align:middle}
.td-name{font-weight:500}

/* ─── BADGES STATUT ─────────────────────── */
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

/* ─── BADGES TYPE CONGÉ ─────────────────── */
.type-badge{display:inline-block;font-size:.68rem;font-weight:500;padding:3px 8px;border-radius:4px}
.t-annuel{background:var(--mint);color:var(--forest)}
.t-maladie{background:var(--info-bg);color:var(--info)}
.t-special{background:#f0e8fb;color:#5a2d82}
.t-sans-solde{background:#f1efe8;color:#7a8f80}

.footer-app{padding:.75rem 1.75rem;border-top:1px solid var(--border);font-size:.75rem;color:var(--muted);background:var(--white);display:flex;align-items:center;gap:6px}
.footer-app span{color:var(--forest);font-weight:500}
</style>
</head>
<body>

<div class="app-wrap">
  <!-- SIDEBAR EMPLOYÉ -->
  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="sidebar-logo-icon"><i class="bi bi-briefcase"></i></div>
      <div class="sidebar-brand-name">TechMada RH<span>Espace employé</span></div>
    </div>
    <div class="sidebar-section">Menu</div>
    <ul class="sidebar-nav">
      <li><a href="<?= site_url('employe/dashboard') ?>" class="active"><i class="bi bi-grid-1x2"></i> Tableau de bord</a></li>
      <li><a href="<?= site_url('employe/nouveau-conge') ?>"><i class="bi bi-plus-circle"></i> Nouvelle demande</a></li>
      <li><a href="<?= site_url('employe/mes-conges') ?>"><i class="bi bi-calendar3"></i> Mes demandes</a></li>
  <li><a href="<?= site_url('employe/profil') ?>"><i class="bi bi-person"></i> Mon profil</a></li>
    </ul>
    <div class="sidebar-user">
      <div class="s-user-row">
        <div class="avatar av-green"><?= substr($session->get('nom'), 0, 1) ?></div>
        <div>
          <div class="user-name"><?= esc($session->get('nom')) ?></div>
          <div class="user-role"><?= esc($session->get('role')) ?></div>
        </div>
      </div>
    </div>
  </aside>

  <div class="main">
    <div class="topbar">
      <div>
        <div class="topbar-title">Mon Espace</div>
      </div>
      <div class="topbar-actions">
        <a href="<?= site_url('logout') ?>" class="icon-btn" title="Déconnexion"><i class="bi bi-box-arrow-right"></i></a>
      </div>
    </div>

    <div class="content">
      <h2>Soldes de congés</h2>
      <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1rem;">
        <?php foreach ($soldes as $row): 
            $restant = $row['jours_attribues'] - $row['jours_pris'];
            $pct = $row['jours_attribues'] > 0 ? min(100, round(($row['jours_pris'] / $row['jours_attribues']) * 100)) : 0;
            $fill_class = '';
            if ($pct > 80) $fill_class = 'danger';
            elseif ($pct > 50) $fill_class = 'warn';

            $type_class = 't-annuel';
            if (stripos(strtolower($row['libelle']), 'maladie') !== false) $type_class = 't-maladie';
            elseif (stripos(strtolower($row['libelle']), 'solde') !== false) $type_class = 't-sans-solde';
        ?>
        <div class="solde-card">
          <div class="solde-header">
            <span class="solde-type"><?= esc($row['libelle']) ?></span>
            <span class="solde-nums"><strong><?= $restant ?></strong> j restants</span>
          </div>
          <div class="solde-bar"><div class="solde-fill <?= $fill_class ?>" style="width:<?= $pct ?>%"></div></div>
          <div class="solde-label">Total acquis : <?= $row['jours_attribues'] ?> j — Pris : <?= $row['jours_pris'] ?> j</div>
        </div>
        <?php endforeach; ?>
      </div>
      
      <br>

      <div class="data-card">
        <div class="data-card-head">
          <h3>Dernières demandes</h3>
        </div>
        <table class="tbl">
          <thead>
            <tr>
              <th>ID</th>
              <th>Type</th>
              <th>Du</th>
              <th>Au</th>
              <th>Durée</th>
              <th>Statut</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($conges as $c): ?>
            <tr>
              <td><?= $c['id'] ?></td>
              <td><?= esc($c['type_conge']) ?></td>
              <td><?= date('d/m/Y', strtotime($c['date_debut'])) ?></td>
              <td><?= date('d/m/Y', strtotime($c['date_fin'])) ?></td>
              <td><?= $c['nb_jours'] ?> j</td>
              <td>
                  <?php if ($c['statut'] == 'en_attente'): ?><span class="statut s-attente">En attente</span>
                  <?php elseif ($c['statut'] == 'approuvee'): ?><span class="statut s-approuvee">Approuvée</span>
                  <?php elseif ($c['statut'] == 'refusee'): ?><span class="statut s-refusee">Refusée</span>
                  <?php elseif ($c['statut'] == 'annulee'): ?><span class="statut s-annulee">Annulée</span>
                  <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($conges)): ?>
            <tr>
                <td colspan="6" style="text-align: center; color: var(--muted); padding: 2rem;">Aucune demande enregistrée.</td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

    </div>
    <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span> — Projet CodeIgniter 4</div>
  </div>

</div>

</body>
</html>
