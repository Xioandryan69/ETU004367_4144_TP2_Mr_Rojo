<?php
$session = session();
$types = $types ?? [];
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
<title>TechMada RH — Types de congé</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet"/>
<style>
:root{--ink:#1c2b1e;--forest:#2d5a3d;--forest2:#3d7a52;--leaf:#5fa876;--mint:#d4ede0;--cream:#f8f6f1;--white:#ffffff;--border:#dde8e1;--muted:#7a8f80;--danger:#c0392b;--danger-bg:#fdf0ee;--danger-br:#f0b8b2;--warn:#b8750a;--warn-bg:#fef9ee;--warn-br:#f5d98a;--success:#1e6b3f;--success-bg:#edf7f2;--success-br:#8fd4aa;--info:#1a4f7a;--info-bg:#eaf2fb;--info-br:#8fbde8;--sidebar-w:240px;--topbar-h:62px}
*{box-sizing:border-box}
body{font-family:'DM Sans',sans-serif;background:var(--cream);color:var(--ink);margin:0;font-size:15px}
.app-wrap{display:flex;min-height:100vh}
.sidebar{width:var(--sidebar-w);background:var(--ink);display:flex;flex-direction:column;flex-shrink:0;position:sticky;top:0;height:100vh;overflow-y:auto}
.sidebar-brand{padding:1.4rem 1.2rem 1rem;display:flex;align-items:center;gap:10px;border-bottom:1px solid rgba(255,255,255,.06)}
.sidebar-logo-icon{width:34px;height:34px;background:var(--ink);border:1px solid rgba(255,255,255,.15);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.sidebar-logo-icon i{color:var(--leaf);font-size:1.1rem}
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
.main{flex:1;min-width:0;display:flex;flex-direction:column}
.topbar{height:var(--topbar-h);background:var(--white);border-bottom:1px solid var(--border);display:flex;align-items:center;padding:0 1.75rem;gap:1rem;position:sticky;top:0;z-index:10}
.topbar-title{font-family:'Playfair Display',serif;font-size:1.05rem;font-weight:600;color:var(--ink)}
.content{padding:1.75rem;flex:1}
.flash{padding:11px 14px;border-radius:8px;font-size:.85rem;font-weight:500;display:flex;align-items:center;gap:9px;margin-bottom:1.25rem;border:1px solid transparent}
.flash-success{background:var(--success-bg);color:var(--success);border-color:var(--success-br)}
.flash-error{background:var(--danger-bg);color:var(--danger);border-color:var(--danger-br)}
.form-section{background:var(--white);border:1px solid var(--border);border-radius:12px;padding:1.5rem;margin-bottom:1.5rem}
.form-section h3{font-family:'Playfair Display',serif;font-size:.95rem;font-weight:600;margin:0 0 1.25rem;color:var(--ink)}
.f-input,.f-select{width:100%;border:1.5px solid var(--border);border-radius:8px;padding:10px 12px;font-size:.875rem;font-family:'DM Sans',sans-serif;background:var(--white);color:var(--ink)}
.f-select:focus,.f-input:focus{border-color:var(--forest);outline:none;box-shadow:0 0 0 3px rgba(45,90,61,.1)}
.f-label{font-size:.8rem;font-weight:500;color:var(--ink);margin-bottom:5px;display:block}
.f-error{font-size:.75rem;color:var(--danger);margin-top:4px}
.form-actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:1.25rem}
.btn-forest{background:var(--forest);color:var(--white);border:none;border-radius:8px;padding:9px 16px;font-size:.85rem;font-weight:500;cursor:pointer;font-family:'DM Sans',sans-serif;display:inline-flex;align-items:center;gap:6px;text-decoration:none;transition:background .15s}
.btn-forest:hover{background:var(--forest2);color:var(--white)}
.btn-sm{font-size:.72rem;font-weight:500;padding:5px 10px;border-radius:6px;border:1px solid transparent;cursor:pointer;transition:all .15s;text-decoration:none;display:inline-flex;align-items:center;gap:4px;font-family:'DM Sans',sans-serif}
.btn-del{background:var(--cream);color:var(--muted);border-color:var(--border)}
.btn-del:hover{background:var(--danger-bg);color:var(--danger)}
.data-card{background:var(--white);border:1px solid var(--border);border-radius:12px;overflow:hidden;margin-bottom:1.5rem}
.data-card-head{padding:.9rem 1.25rem;border-bottom:1px solid var(--border)}
.tbl{width:100%;border-collapse:collapse;font-size:.85rem}
.tbl thead th{padding:9px 14px;font-size:.68rem;font-weight:500;text-transform:uppercase;letter-spacing:.07em;color:var(--muted);background:var(--cream);border-bottom:1px solid var(--border);text-align:left;white-space:nowrap}
.tbl tbody tr{border-bottom:1px solid var(--border);transition:background .1s}
.tbl tbody tr:last-child{border-bottom:none}
.tbl tbody tr:hover{background:var(--cream)}
.tbl td{padding:12px 14px;color:var(--ink);vertical-align:middle}
.footer-app{padding:.75rem 1.75rem;border-top:1px solid var(--border);font-size:.75rem;color:var(--muted);background:var(--white);display:flex;align-items:center;gap:6px}
.footer-app span{color:var(--forest);font-weight:500}
</style>
</head>
<body>
<div class="app-wrap">
  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="sidebar-logo-icon"><i class="bi bi-shield-check"></i></div>
      <div class="sidebar-brand-name">TechMada RH<span>Administration</span></div>
    </div>
    <ul class="sidebar-nav">
      <li><a href="<?= site_url('admin') ?>"><i class="bi bi-speedometer2"></i> Vue d'ensemble</a></li>
      <li><a href="<?= site_url('admin/employes') ?>"><i class="bi bi-people"></i> Employés</a></li>
      <li><a href="<?= site_url('admin/departements') ?>"><i class="bi bi-building"></i> Départements</a></li>
      <li><a href="<?= site_url('admin/types-conge') ?>" class="active"><i class="bi bi-tags"></i> Types de congé</a></li>
      <li><a href="<?= site_url('admin/soldes') ?>"><i class="bi bi-sliders"></i> Soldes annuels</a></li>
    </ul>
    <div class="sidebar-user">
      <div class="s-user-row">
        <div class="avatar" style="background:#5a2d82">AD</div>
        <div>
          <div class="user-name"><?= esc((string) $session->get('nom')) ?></div>
          <div class="user-role">Admin système</div>
        </div>
        <a href="<?= site_url('logout') ?>" style="margin-left:auto;color:rgba(255,255,255,.25);font-size:1.1rem"><i class="bi bi-box-arrow-right"></i></a>
      </div>
    </div>
  </aside>

  <div class="main">
    <div class="topbar"><div class="topbar-title">Types de congé</div></div>
    <div class="content">
      <?php if ($successMessage): ?>
        <div class="flash flash-success"><i class="bi bi-check-circle-fill"></i><?= esc($successMessage) ?></div>
      <?php endif; ?>
      <?php if ($errorMessage): ?>
        <div class="flash flash-error"><i class="bi bi-exclamation-circle-fill"></i><?= esc($errorMessage) ?></div>
      <?php endif; ?>

      <div class="form-section">
        <h3>Ajouter un type</h3>
        <form action="<?= site_url('admin/types-conge') ?>" method="post">
          <?= csrf_field() ?>
          <div class="form-grid-2" style="margin-bottom:1rem">
            <div class="f-group">
              <label class="f-label">Libellé</label>
              <input type="text" name="libelle" class="f-input" value="<?= esc(old('libelle')) ?>" />
              <?php if (isset($errors['libelle'])): ?><div class="f-error"><?= esc(is_string($errors['libelle']) ? $errors['libelle'] : '') ?></div><?php endif; ?>
            </div>
            <div class="f-group">
              <label class="f-label">Jours annuels</label>
              <input type="number" name="jours_annuels" class="f-input" value="<?= esc(old('jours_annuels')) ?>" />
              <?php if (isset($errors['jours_annuels'])): ?><div class="f-error"><?= esc(is_string($errors['jours_annuels']) ? $errors['jours_annuels'] : '') ?></div><?php endif; ?>
            </div>
            <div class="f-group">
              <label class="f-label">Déductible</label>
              <select class="f-select" name="deductible">
                <option value="1" <?= old('deductible') === '1' ? 'selected' : '' ?>>Oui</option>
                <option value="0" <?= old('deductible') === '0' ? 'selected' : '' ?>>Non</option>
              </select>
              <?php if (isset($errors['deductible'])): ?><div class="f-error"><?= esc(is_string($errors['deductible']) ? $errors['deductible'] : '') ?></div><?php endif; ?>
            </div>
          </div>
          <div class="form-actions"><button class="btn-forest" type="submit"><i class="bi bi-plus"></i> Ajouter</button></div>
        </form>
      </div>

      <div class="data-card">
        <div class="data-card-head"><h3>Liste des types</h3></div>
        <table class="tbl">
          <thead><tr><th>Libellé</th><th>Jours annuels</th><th>Déductible</th><th>Actions</th></tr></thead>
          <tbody>
            <?php foreach ($types as $type): ?>
              <tr>
                <td><?= esc((string) $type['libelle']) ?></td>
                <td><?= (int) $type['jours_annuels'] ?></td>
                <td><?= (int) $type['deductible'] === 1 ? 'Oui' : 'Non' ?></td>
                <td>
                  <form action="<?= site_url('admin/types-conge/delete/'.(int)$type['id']) ?>" method="post" style="margin:0">
                    <?= csrf_field() ?>
                    <button class="btn-sm btn-del" type="submit" onclick="return confirm('Supprimer ce type de congé ?')"><i class="bi bi-trash"></i> Supprimer</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (empty($types)): ?>
              <tr><td colspan="4" style="text-align:center;color:var(--muted);padding:1.5rem">Aucun type.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
    <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
  </div>
</div>
</body>
</html>
