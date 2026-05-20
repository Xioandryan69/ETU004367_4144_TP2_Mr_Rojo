<?php
$session = session();
$employe = $employe ?? [];
$errors = session('errors') ?? [];
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
<title>TechMada RH — Mon profil</title>
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
.content{padding:1.75rem;flex:1}
.flash{padding:11px 14px;border-radius:8px;font-size:.85rem;font-weight:500;display:flex;align-items:center;gap:9px;margin-bottom:1.25rem;border:1px solid transparent}
.flash-success{background:var(--success-bg);color:var(--success);border-color:var(--success-br)}
.flash-error{background:var(--danger-bg);color:var(--danger);border-color:var(--danger-br)}
.form-section{background:var(--white);border:1px solid var(--border);border-radius:12px;padding:1.5rem;margin-bottom:1.5rem}
.form-section h3{font-family:'Playfair Display',serif;font-size:.95rem;font-weight:600;margin:0 0 1.25rem;color:var(--ink)}
.form-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
@media(max-width:600px){.form-grid-2{grid-template-columns:1fr}}
.f-input{width:100%;border:1.5px solid var(--border);border-radius:8px;padding:10px 12px;font-size:.875rem;font-family:'DM Sans',sans-serif;background:var(--white);color:var(--ink)}
.f-input:focus{border-color:var(--forest);outline:none;box-shadow:0 0 0 3px rgba(45,90,61,.1)}
.f-label{font-size:.8rem;font-weight:500;color:var(--ink);margin-bottom:5px;display:block}
.f-error{font-size:.75rem;color:var(--danger);margin-top:4px}
.form-actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:1.25rem}
.btn-secondary{background:var(--white);color:var(--muted);border:1.5px solid var(--border);border-radius:8px;padding:9px 16px;font-size:.85rem;font-weight:500;cursor:pointer;font-family:'DM Sans',sans-serif;display:inline-flex;align-items:center;gap:6px;text-decoration:none;transition:all .15s}
.btn-secondary:hover{border-color:var(--muted);color:var(--ink)}
.btn-forest{background:var(--forest);color:var(--white);border:none;border-radius:8px;padding:9px 16px;font-size:.85rem;font-weight:500;cursor:pointer;font-family:'DM Sans',sans-serif;display:inline-flex;align-items:center;gap:6px;text-decoration:none;transition:background .15s}
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
      <li><a href="<?= site_url('employe/mes-conges') ?>"><i class="bi bi-calendar3"></i> Mes demandes</a></li>
      <li><a href="<?= site_url('employe/profil') ?>" class="active"><i class="bi bi-person"></i> Mon profil</a></li>
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
        <div class="topbar-title">Mon profil</div>
      </div>
    </div>

    <div class="content">
      <?php if ($successMessage): ?>
        <div class="flash flash-success"><i class="bi bi-check-circle-fill"></i><?= esc($successMessage) ?></div>
      <?php endif; ?>
      <?php if ($errorMessage): ?>
        <div class="flash flash-error"><i class="bi bi-exclamation-circle-fill"></i><?= esc($errorMessage) ?></div>
      <?php endif; ?>

      <div class="form-section">
        <h3>Mes informations</h3>
        <form action="<?= site_url('employe/profil') ?>" method="post">
          <?= csrf_field() ?>
          <div class="form-grid-2" style="margin-bottom:1rem">
            <div class="f-group">
              <label class="f-label">Prénom</label>
              <input type="text" name="prenom" class="f-input" value="<?= esc((string) ($employe['prenom'] ?? '')) ?>" required/>
              <?php if (isset($errors['prenom'])): ?>
                <div class="f-error"><i class="bi bi-exclamation-circle"></i> <?= esc(is_string($errors['prenom']) ? $errors['prenom'] : '') ?></div>
              <?php endif; ?>
            </div>
            <div class="f-group">
              <label class="f-label">Nom</label>
              <input type="text" name="nom" class="f-input" value="<?= esc((string) ($employe['nom'] ?? '')) ?>" required/>
              <?php if (isset($errors['nom'])): ?>
                <div class="f-error"><i class="bi bi-exclamation-circle"></i> <?= esc(is_string($errors['nom']) ? $errors['nom'] : '') ?></div>
              <?php endif; ?>
            </div>
          </div>

          <div class="f-group" style="margin-bottom:1rem">
            <label class="f-label">Mot de passe (laisser vide pour conserver)</label>
            <input type="password" name="password" class="f-input" placeholder="••••••••" />
            <?php if (isset($errors['password'])): ?>
              <div class="f-error"><i class="bi bi-exclamation-circle"></i> <?= esc(is_string($errors['password']) ? $errors['password'] : '') ?></div>
            <?php endif; ?>
          </div>

          <div class="form-actions">
            <button class="btn-forest" type="submit"><i class="bi bi-save"></i> Enregistrer</button>
            <a class="btn-secondary" href="<?= site_url('employe/dashboard') ?>"><i class="bi bi-x"></i> Retour</a>
          </div>
        </form>
      </div>
    </div>
    <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
  </div>
</div>
</body>
</html>
