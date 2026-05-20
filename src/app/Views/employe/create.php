<?php
$session = session();
$errors = session('errors') ?? [];
$success = session('success');
$error = session('error');
$successMessage = is_string($success) ? $success : '';
$errorMessage = is_string($error) ? $error : '';
$typesConge = $typesConge ?? [];
$soldes = $soldes ?? [];

$soldeByType = [];
foreach ($soldes ?? [] as $solde) {
    $soldeByType[$solde['type_conge_id']] = $solde;
}

$computedDays = null;
$dateDebut = old('date_debut');
$dateFin = old('date_fin');
if ($dateDebut && $dateFin && strtotime($dateDebut) <= strtotime($dateFin)) {
    $start = new DateTime($dateDebut);
    $end = new DateTime($dateFin);
    $end->setTime(0, 0, 1);
    $days = 0;
    while ($start <= $end) {
        if ((int) $start->format('N') < 6) {
            $days++;
        }
        $start->modify('+1 day');
    }
    $computedDays = $days;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<title>TechMada RH — Nouvelle demande</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet"/>
<style>
:root{
  --ink:#1c2b1e;--forest:#2d5a3d;--forest2:#3d7a52;--leaf:#5fa876;--mint:#d4ede0;--cream:#f8f6f1;--white:#ffffff;--border:#dde8e1;--muted:#7a8f80;--danger:#c0392b;--danger-bg:#fdf0ee;--danger-br:#f0b8b2;--warn:#b8750a;--warn-bg:#fef9ee;--warn-br:#f5d98a;--success:#1e6b3f;--success-bg:#edf7f2;--success-br:#8fd4aa;--info:#1a4f7a;--info-bg:#eaf2fb;--info-br:#8fbde8;--sidebar-w:240px;--topbar-h:62px;
}
*{box-sizing:border-box}
body{font-family:'DM Sans',sans-serif;background:var(--cream);color:var(--ink);margin:0;font-size:15px}
h1,h2,h3,.brand-name{font-family:'Playfair Display',serif}
code,pre,.mono{font-family:'DM Mono',monospace}
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
.topbar-breadcrumb{font-size:.78rem;color:var(--muted);display:flex;align-items:center;gap:5px}
.topbar-breadcrumb a{color:var(--muted);text-decoration:none}
.topbar-breadcrumb a:hover{color:var(--forest)}
.content{padding:1.75rem;flex:1}
.flash{padding:11px 14px;border-radius:8px;font-size:.85rem;font-weight:500;display:flex;align-items:center;gap:9px;margin-bottom:1.25rem;border:1px solid transparent}
.flash-success{background:var(--success-bg);color:var(--success);border-color:var(--success-br)}
.flash-error{background:var(--danger-bg);color:var(--danger);border-color:var(--danger-br)}
.form-section{background:var(--white);border:1px solid var(--border);border-radius:12px;padding:1.5rem;margin-bottom:1.5rem}
.form-section h3{font-family:'Playfair Display',serif;font-size:.95rem;font-weight:600;margin:0 0 1.25rem;color:var(--ink)}
.form-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
@media(max-width:600px){.form-grid-2{grid-template-columns:1fr}}
.f-select,.f-input,.f-textarea{width:100%;border:1.5px solid var(--border);border-radius:8px;padding:10px 12px;font-size:.875rem;font-family:'DM Sans',sans-serif;background:var(--white);color:var(--ink)}
.f-select:focus,.f-input:focus,.f-textarea:focus{border-color:var(--forest);outline:none;box-shadow:0 0 0 3px rgba(45,90,61,.1)}
.f-textarea{resize:vertical;min-height:80px}
.f-label{font-size:.8rem;font-weight:500;color:var(--ink);margin-bottom:5px;display:block}
.f-error{font-size:.75rem;color:var(--danger);margin-top:4px}
.form-actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:1.25rem}
.btn-secondary{background:var(--white);color:var(--muted);border:1.5px solid var(--border);border-radius:8px;padding:9px 16px;font-size:.85rem;font-weight:500;cursor:pointer;font-family:'DM Sans',sans-serif;display:inline-flex;align-items:center;gap:6px;text-decoration:none;transition:all .15s}
.btn-secondary:hover{border-color:var(--muted);color:var(--ink)}
.btn-forest{background:var(--forest);color:var(--white);border:none;border-radius:8px;padding:9px 16px;font-size:.85rem;font-weight:500;cursor:pointer;font-family:'DM Sans',sans-serif;display:inline-flex;align-items:center;gap:6px;text-decoration:none;transition:background .15s}
.btn-forest:hover{background:var(--forest2);color:var(--white)}
.solde-bar{height:6px;background:var(--mint);border-radius:3px;overflow:hidden}
.solde-fill{height:100%;background:var(--forest2);border-radius:3px;transition:width .3s}
.solde-fill.warn{background:var(--warn)}
.data-card{background:var(--white);border:1px solid var(--border);border-radius:12px;overflow:hidden;margin-bottom:1.5rem}
.data-card-head{padding:.9rem 1.25rem;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:.75rem;flex-wrap:wrap}
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
      <li><a href="<?= site_url('employe/nouveau-conge') ?>" class="active"><i class="bi bi-plus-circle"></i> Nouvelle demande</a></li>
      <li><a href="<?= site_url('employe/mes-conges') ?>"><i class="bi bi-calendar3"></i> Mes demandes</a></li>
      <li><a href="<?= site_url('employe/profil') ?>"><i class="bi bi-person"></i> Mon profil</a></li>
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
        <div class="topbar-title">Nouvelle demande de congé</div>
        <div class="topbar-breadcrumb"><a href="<?= site_url('employe/dashboard') ?>">Accueil</a> <i class="bi bi-chevron-right" style="font-size:.6rem"></i> Nouvelle demande</div>
      </div>
    </div>

    <div class="content">
      <?php if ($successMessage): ?>
        <div class="flash flash-success"><i class="bi bi-check-circle-fill"></i><?= esc($successMessage) ?></div>
      <?php endif; ?>
      <?php if ($errorMessage): ?>
        <div class="flash flash-error"><i class="bi bi-exclamation-circle-fill"></i><?= esc($errorMessage) ?></div>
      <?php endif; ?>

      <div style="display:grid;grid-template-columns:1fr 300px;gap:1.5rem;align-items:start" class="form-layout">
        <div>
          <div class="form-section">
            <h3>Détails de la demande</h3>
            <form action="<?= site_url('employe/nouveau-conge') ?>" method="post">
              <?= csrf_field() ?>
              <div class="f-group" style="margin-bottom:1rem">
                <label class="f-label">Type de congé <span style="color:var(--danger)">*</span></label>
                <select class="f-select" name="type_conge_id" required>
                  <option value="">-- Choisir un type --</option>
                  <?php foreach ($typesConge as $type):
                    $solde = $soldeByType[$type['id']] ?? null;
                    $restant = $solde ? ((int) $solde['jours_attribues'] - (int) $solde['jours_pris']) : null;
                  ?>
                    <option value="<?= $type['id'] ?>" <?= old('type_conge_id') == $type['id'] ? 'selected' : '' ?>>
                      <?= esc((string) ($type['libelle'] ?? '')) ?><?= $restant !== null ? ' (' . $restant . ' j restants)' : '' ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <?php if (isset($errors['type_conge_id'])): ?>
                  <div class="f-error"><i class="bi bi-exclamation-circle"></i> <?= esc(is_string($errors['type_conge_id']) ? $errors['type_conge_id'] : '') ?></div>
                <?php endif; ?>
              </div>

              <div class="form-grid-2" style="margin-bottom:1rem">
                <div class="f-group">
                  <label class="f-label">Date de début <span style="color:var(--danger)">*</span></label>
                  <input type="date" name="date_debut" class="f-input" value="<?= esc(old('date_debut')) ?>" required/>
                  <?php if (isset($errors['date_debut'])): ?>
                    <div class="f-error"><i class="bi bi-exclamation-circle"></i> <?= esc(is_string($errors['date_debut']) ? $errors['date_debut'] : '') ?></div>
                  <?php endif; ?>
                </div>
                <div class="f-group">
                  <label class="f-label">Date de fin <span style="color:var(--danger)">*</span></label>
                  <input type="date" name="date_fin" class="f-input" value="<?= esc(old('date_fin')) ?>" required/>
                  <?php if (isset($errors['date_fin'])): ?>
                    <div class="f-error"><i class="bi bi-exclamation-circle"></i> <?= esc(is_string($errors['date_fin']) ? $errors['date_fin'] : '') ?></div>
                  <?php endif; ?>
                </div>
              </div>

              <?php if ($computedDays !== null): ?>
                <div class="f-computed" style="background:var(--mint);border:1px solid #9fd4b8;border-radius:8px;padding:10px 14px;margin:1rem 0;display:flex;align-items:center;gap:10px">
                  <div class="f-computed-num" style="font-family:'DM Mono',monospace;font-size:1.3rem;font-weight:500;color:var(--forest)"><?= $computedDays ?></div>
                  <div class="f-computed-label" style="font-size:.8rem;color:var(--forest)">jours ouvrables calculés</div>
                </div>
              <?php endif; ?>

              <div class="f-group" style="margin-bottom:1rem">
                <label class="f-label">Motif (optionnel)</label>
                <textarea class="f-textarea" name="motif" placeholder="Précisez le motif de votre demande si nécessaire..."><?= esc(old('motif')) ?></textarea>
              </div>

              <div class="form-actions">
                <button class="btn-forest" type="submit"><i class="bi bi-send"></i> Soumettre la demande</button>
                <a href="<?= site_url('employe/dashboard') ?>" class="btn-secondary"><i class="bi bi-x"></i> Annuler</a>
              </div>
            </form>
          </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:1rem">
          <div class="data-card" style="margin:0">
            <div class="data-card-head"><h3><i class="bi bi-piggy-bank" style="color:var(--forest);margin-right:5px"></i>Vos soldes actuels</h3></div>
            <div style="padding:.75rem 1.1rem;display:flex;flex-direction:column;gap:.75rem">
              <?php foreach ($soldes as $solde):
                $restant = (int) $solde['jours_attribues'] - (int) $solde['jours_pris'];
                $pct = (int) $solde['jours_attribues'] > 0 ? min(100, round(($solde['jours_pris'] / $solde['jours_attribues']) * 100)) : 0;
                $fillClass = $pct > 80 ? 'warn' : '';
              ?>
                <div>
                  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px">
                    <span style="font-size:.8rem;color:var(--ink)"><?= esc((string) ($solde['libelle'] ?? '')) ?></span>
                    <span style="font-family:'DM Mono',monospace;font-size:.8rem;color:var(--forest);font-weight:500"><?= $restant ?> j</span>
                  </div>
                  <div class="solde-bar"><div class="solde-fill <?= $fillClass ?>" style="width:<?= $pct ?>%"></div></div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
          <div class="flash flash-info" style="margin:0;background:var(--info-bg);color:var(--info);border-color:var(--info-br)">
            <i class="bi bi-info-circle-fill"></i>
            <span style="font-size:.8rem">Le solde est déduit uniquement à l'approbation de votre responsable.</span>
          </div>
        </div>
      </div>
    </div>
    <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
  </div>
</div>
</body>
</html>
