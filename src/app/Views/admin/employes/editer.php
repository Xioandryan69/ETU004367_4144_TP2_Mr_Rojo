<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<div class="form-section">
    <h3>Editer employe</h3>
    <form method="post" action="/admin/employes/<?= esc($employe['id']) ?>/editer">
        <?= csrf_field() ?>
        <div class="form-grid-2">
            <div class="f-group">
                <label class="f-label">Nom</label>
                <input class="f-input" name="nom" value="<?= esc($employe['nom']) ?>" required/>
            </div>
            <div class="f-group">
                <label class="f-label">Prenom</label>
                <input class="f-input" name="prenom" value="<?= esc($employe['prenom']) ?>" required/>
            </div>
            <div class="f-group">
                <label class="f-label">Email</label>
                <input type="email" class="f-input" name="email" value="<?= esc($employe['email']) ?>" required/>
            </div>
            <div class="f-group">
                <label class="f-label">Mot de passe (laisser vide)</label>
                <input type="password" class="f-input" name="password"/>
            </div>
            <div class="f-group">
                <label class="f-label">Departement</label>
                <select class="f-select" name="departement_id" required>
                    <?php foreach ($departements as $departement) : ?>
                        <option value="<?= esc($departement['id']) ?>" <?= ((int) $departement['id'] === (int) $employe['departement_id']) ? 'selected' : '' ?>>
                            <?= esc($departement['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="f-group">
                <label class="f-label">Role</label>
                <select class="f-select" name="role" required>
                    <option value="employe" <?= $employe['role'] === 'employe' ? 'selected' : '' ?>>Employe</option>
                    <option value="rh" <?= $employe['role'] === 'rh' ? 'selected' : '' ?>>RH</option>
                    <option value="admin" <?= $employe['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>
            </div>
            <div class="f-group">
                <label class="f-label">Date d'embauche</label>
                <input type="date" class="f-input" name="date_embauche" value="<?= esc($employe['date_embauche']) ?>" required/>
            </div>
            <div class="f-group">
                <label class="f-label">Statut</label>
                <select class="f-select" name="actif" required>
                    <option value="1" <?= (int) $employe['actif'] === 1 ? 'selected' : '' ?>>Actif</option>
                    <option value="0" <?= (int) $employe['actif'] === 0 ? 'selected' : '' ?>>Inactif</option>
                </select>
            </div>
        </div>
        <div class="form-actions">
            <button class="btn-forest" type="submit"><i class="bi bi-check-lg"></i> Enregistrer</button>
            <a class="btn-secondary" href="/admin/employes"><i class="bi bi-arrow-left"></i> Retour</a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
