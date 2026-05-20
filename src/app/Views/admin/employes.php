<?php
$session = session();
$employes = $employes ?? [];
$departements = $departements ?? [];
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
<title>TechMada RH — Employés</title>
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
.form-section{background:var(--white);border:1px solid var(--border);border-radius:12px;padding:1.5rem;margin-bottom:1.5rem}
.form-section h3{font-family:'Playfair Display',serif;font-size:.95rem;font-weight:600;margin:0 0 1.25rem;color:var(--ink)}
.form-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
@media(max-width:600px){.form-grid-2{grid-template-columns:1fr}}
.f-input,.f-select{width:100%;border:1.5px solid var(--border);border-radius:8px;padding:10px 12px;font-size:.875rem;font-family:'DM Sans',sans-serif;background:var(--white);color:var(--ink)}
.f-input:focus,.f-select:focus{border-color:var(--forest);outline:none;box-shadow:0 0 0 3px rgba(45,90,61,.1)}
.f-label{font-size:.8rem;font-weight:500;color:var(--ink);margin-bottom:5px;display:block}
.f-error{font-size:.75rem;color:var(--danger);margin-top:4px}
.form-actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:1.25rem}
.btn-forest{background:var(--forest);color:var(--white);border:none;border-radius:8px;padding:9px 16px;font-size:.85rem;font-weight:500;cursor:pointer;font-family:'DM Sans',sans-serif;display:inline-flex;align-items:center;gap:6px;text-decoration:none;transition:background .15s}
.btn-forest:hover{background:var(--forest2);color:var(--white)}
.btn-secondary{background:var(--white);color:var(--muted);border:1.5px solid var(--border);border-radius:8px;padding:9px 16px;font-size:.85rem;font-weight:500;cursor:pointer;font-family:'DM Sans',sans-serif;display:inline-flex;align-items:center;gap:6px;text-decoration:none;transition:all .15s}
.btn-secondary:hover{border-color:var(--muted);color:var(--ink)}
.data-card{background:var(--white);border:1px solid var(--border);border-radius:12px;overflow:hidden;margin-bottom:1.5rem}
.data-card-head{padding:.9rem 1.25rem;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:.75rem;flex-wrap:wrap}
.tbl{width:100%;border-collapse:collapse;font-size:.85rem}
.tbl thead th{padding:9px 14px;font-size:.68rem;font-weight:500;text-transform:uppercase;letter-spacing:.07em;color:var(--muted);background:var(--cream);border-bottom:1px solid var(--border);text-align:left;white-space:nowrap}
.tbl tbody tr{border-bottom:1px solid var(--border);transition:background .1s}
.tbl tbody tr:last-child{border-bottom:none}
.tbl tbody tr:hover{background:var(--cream)}
.tbl td{padding:12px 14px;color:var(--ink);vertical-align:middle}
.profile-row{display:flex;align-items:center;gap:12px}
.profile-row .avatar{width:32px;height:32px;font-size:.7rem}
.profile-info .pname{font-weight:500;font-size:.9rem;color:var(--ink)}
.profile-info .pdept{font-size:.75rem;color:var(--muted)}
.statut{display:inline-flex;align-items:center;gap:5px;font-size:.7rem;font-weight:500;padding:4px 9px;border-radius:12px}
.s-approuvee{background:var(--success-bg);color:var(--success)}
.s-annulee{background:#f1efe8;color:#7a8f80}
.action-btns{display:flex;gap:5px;flex-wrap:wrap}
.btn-sm{font-size:.72rem;font-weight:500;padding:5px 10px;border-radius:6px;border:1px solid transparent;cursor:pointer;transition:all .15s;text-decoration:none;display:inline-flex;align-items:center;gap:4px;font-family:'DM Sans',sans-serif}
.btn-edit{background:var(--info-bg);color:var(--info);border-color:var(--info-br)}
.btn-edit:hover{background:#d5e8f7}
.btn-del{background:var(--cream);color:var(--muted);border-color:var(--border)}
.btn-del:hover{background:var(--danger-bg);color:var(--danger)}
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
      <li><a href="<?= site_url('admin/employes') ?>" class="active"><i class="bi bi-people"></i> Employés</a></li>
      <li><a href="<?= site_url('admin/departements') ?>"><i class="bi bi-building"></i> Départements</a></li>
      <li><a href="<?= site_url('admin/types-conge') ?>"><i class="bi bi-tags"></i> Types de congé</a></li>
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
    <div class="topbar">
      <div>
        <div class="topbar-title">Gestion des employés</div>
      </div>
      <div class="topbar-actions">
        <a href="#form-ajout" class="btn-forest"><i class="bi bi-person-plus"></i> Ajouter</a>
      </div>
    </div>

    <div class="content">
      <?php if ($successMessage): ?>
        <div class="flash flash-success"><i class="bi bi-check-circle-fill"></i><?= esc($successMessage) ?></div>
      <?php endif; ?>
      <?php if ($errorMessage): ?>
        <div class="flash flash-error"><i class="bi bi-exclamation-circle-fill"></i><?= esc($errorMessage) ?></div>
      <?php endif; ?>

      <div class="form-section" id="form-ajout">
        <h3><i class="bi bi-person-plus" style="color:var(--forest);margin-right:6px"></i>Ajouter un employé</h3>
        <form action="<?= site_url('admin/employes') ?>" method="post">
          <?= csrf_field() ?>
          <div class="form-grid-2" style="margin-bottom:1rem">
            <div class="f-group">
              <label class="f-label">Prénom</label>
              <input type="text" name="prenom" class="f-input" value="<?= esc(old('prenom')) ?>" />
              <?php if (isset($errors['prenom'])): ?><div class="f-error"><?= esc(is_string($errors['prenom']) ? $errors['prenom'] : '') ?></div><?php endif; ?>
            </div>
            <div class="f-group">
              <label class="f-label">Nom</label>
              <input type="text" name="nom" class="f-input" value="<?= esc(old('nom')) ?>" />
              <?php if (isset($errors['nom'])): ?><div class="f-error"><?= esc(is_string($errors['nom']) ? $errors['nom'] : '') ?></div><?php endif; ?>
            </div>
            <div class="f-group">
              <label class="f-label">Email</label>
              <input type="email" name="email" class="f-input" value="<?= esc(old('email')) ?>" />
              <?php if (isset($errors['email'])): ?><div class="f-error"><?= esc(is_string($errors['email']) ? $errors['email'] : '') ?></div><?php endif; ?>
            </div>
            <div class="f-group">
              <label class="f-label">Mot de passe</label>
              <input type="password" name="password" class="f-input" />
              <?php if (isset($errors['password'])): ?><div class="f-error"><?= esc(is_string($errors['password']) ? $errors['password'] : '') ?></div><?php endif; ?>
            </div>
            <div class="f-group">
              <label class="f-label">Département</label>
              <select class="f-select" name="departement_id">
                <option value="">Choisir</option>
                <?php foreach ($departements as $departement): ?>
                  <option value="<?= (int) $departement['id'] ?>" <?= old('departement_id') == $departement['id'] ? 'selected' : '' ?>>
                    <?= esc((string) $departement['nom']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <?php if (isset($errors['departement_id'])): ?><div class="f-error"><?= esc(is_string($errors['departement_id']) ? $errors['departement_id'] : '') ?></div><?php endif; ?>
            </div>
            <div class="f-group">
              <label class="f-label">Rôle</label>
              <select class="f-select" name="role">
                <option value="employe" <?= old('role') === 'employe' ? 'selected' : '' ?>>Employé</option>
                <option value="rh" <?= old('role') === 'rh' ? 'selected' : '' ?>>Responsable RH</option>
                <option value="admin" <?= old('role') === 'admin' ? 'selected' : '' ?>>Administrateur</option>
              </select>
            </div>
            <div class="f-group">
              <label class="f-label">Date d'embauche</label>
              <input type="date" name="date_embauche" class="f-input" value="<?= esc(old('date_embauche')) ?>" />
              <?php if (isset($errors['date_embauche'])): ?><div class="f-error"><?= esc(is_string($errors['date_embauche']) ? $errors['date_embauche'] : '') ?></div><?php endif; ?>
            </div>
          </div>
          <div class="form-actions">
            <button class="btn-forest" type="submit"><i class="bi bi-plus"></i> Créer l'employé</button>
            <a class="btn-secondary" href="<?= site_url('admin/employes') ?>">Réinitialiser</a>
          </div>
        </form>
      </div>

      <div class="data-card">
        <div class="data-card-head"><h3>Tous les employés</h3></div>
        <table class="tbl">
          <thead>
            <tr><th>Employé</th><th>Département</th><th>Rôle</th><th>Embauche</th><th>Statut</th><th>Actions</th></tr>
          </thead>
          <tbody>
            <?php foreach ($employes as $employe):
              $initials = substr((string) $employe['prenom'], 0, 1) . substr((string) $employe['nom'], 0, 1);
            ?>
              <tr>
                <td>
                  <div class="profile-row">
                    <div class="avatar" style="background:#5a2d82"><?= esc(strtoupper($initials)) ?></div>
                    <div class="profile-info">
                      <div class="pname"><?= esc((string) $employe['prenom']) ?> <?= esc((string) $employe['nom']) ?></div>
                      <div class="pdept"><?= esc((string) $employe['email']) ?></div>
                    </div>
                  </div>
                </td>
                <td class="td-muted"><?= esc((string) ($employe['departement'] ?? '')) ?></td>
                <td><?= esc((string) $employe['role']) ?></td>
                <td class="td-muted"><?= esc((string) $employe['date_embauche']) ?></td>
                <td><span class="statut <?= (int) $employe['actif'] === 1 ? 's-approuvee' : 's-annulee' ?>"><?= (int) $employe['actif'] === 1 ? 'actif' : 'inactif' ?></span></td>
                <td>
                  <div style="display:flex;gap:6px;align-items:center">
                    <button class="btn-sm btn-edit" onclick="openEditModal(<?= (int)$employe['id'] ?>, '<?= esc($employe['prenom']) ?>', '<?= esc($employe['nom']) ?>', <?= (int)$employe['departement_id'] ?>, '<?= esc($employe['role']) ?>')"><i class="bi bi-pencil"></i> Modifier</button>
                    <form action="<?= site_url('admin/employes/toggle/'.(int)$employe['id']) ?>" method="post" style="margin:0">
                      <?= csrf_field() ?>
                      <button class="btn-sm <?= (int)$employe['actif'] === 1 ? 'btn-del' : 'btn-edit' ?>" type="submit" onclick="return confirm('Confirmer ?')"><?= (int)$employe['actif'] === 1 ? '<i class=\'bi bi-toggle-on\'></i> Désactiver' : '<i class=\'bi bi-toggle-off\'></i> Réactiver' ?></button>
                    </form>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (empty($employes)): ?>
              <tr><td colspan="6" style="text-align:center;color:var(--muted);padding:1.5rem">Aucun employé.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
    <div class="footer-app"><i class="bi bi-c-circle"></i> 2025 <span>TechMada RH</span></div>
  </div>
</div>

<!-- Modal Modifier Employé -->
<div id="editModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:1000;align-items:center;justify-content:center">
  <div style="background:var(--white);border-radius:14px;padding:2rem;width:100%;max-width:480px;margin:1rem">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem">
      <h3 style="margin:0;font-family:'Playfair Display',serif;font-size:1.1rem">Modifier l'employé</h3>
      <button onclick="closeEditModal()" style="background:none;border:none;font-size:1.3rem;cursor:pointer;color:var(--muted)">&times;</button>
    </div>
    <form id="editForm" method="post">
      <?= csrf_field() ?>
      <div class="form-grid-2" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem">
        <div><label class="f-label">Prénom</label><input type="text" name="prenom" id="edit_prenom" class="f-input" required></div>
        <div><label class="f-label">Nom</label><input type="text" name="nom" id="edit_nom" class="f-input" required></div>
      </div>
      <div style="margin-bottom:1rem">
        <label class="f-label">Département</label>
        <select name="departement_id" id="edit_dept" class="f-input" style="appearance:auto">
          <?php foreach ($departements as $d): ?>
            <option value="<?= (int)$d['id'] ?>"><?= esc($d['nom']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div style="margin-bottom:1rem">
        <label class="f-label">Rôle</label>
        <select name="role" id="edit_role" class="f-input" style="appearance:auto">
          <option value="employe">Employé</option>
          <option value="rh">RH</option>
          <option value="admin">Admin</option>
        </select>
      </div>
      <div style="margin-bottom:1.25rem">
        <label class="f-label">Nouveau mot de passe <span style="color:var(--muted);font-weight:400">(laisser vide pour ne pas changer)</span></label>
        <input type="password" name="password" class="f-input" placeholder="min. 6 caractères">
      </div>
      <div style="display:flex;gap:10px">
        <button type="submit" class="btn-forest"><i class="bi bi-check2"></i> Enregistrer</button>
        <button type="button" onclick="closeEditModal()" class="btn-secondary">Annuler</button>
      </div>
    </form>
  </div>
</div>
<script>
function openEditModal(id, prenom, nom, deptId, role) {
  document.getElementById('edit_prenom').value = prenom;
  document.getElementById('edit_nom').value = nom;
  document.getElementById('edit_dept').value = deptId;
  document.getElementById('edit_role').value = role;
  document.getElementById('editForm').action = '/admin/employes/update/' + id;
  const m = document.getElementById('editModal');
  m.style.display = 'flex';
}
function closeEditModal() {
  document.getElementById('editModal').style.display = 'none';
}
document.getElementById('editModal').addEventListener('click', function(e) {
  if (e.target === this) closeEditModal();
});
</script>
</body>
</html>
