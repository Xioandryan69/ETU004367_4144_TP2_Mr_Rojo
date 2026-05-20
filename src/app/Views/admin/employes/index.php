<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<div class="form-section">
    <h3>Ajouter un employé</h3>
    <form method="post" action="/admin/employes/creer">
        <?= csrf_field() ?>
        <div class="form-grid-2">
            <div class="f-group">
                <label class="f-label">Prénom</label>
                <input class="f-input" name="prenom" placeholder="Jean" required/>
            </div>
            <div class="f-group">
                <label class="f-label">Nom</label>
                <input class="f-input" name="nom" placeholder="Rakoto" required/>
            </div>
            <div class="f-group">
                <label class="f-label">Email</label>
                <input type="email" class="f-input" name="email" placeholder="jean.rakoto@techmada.mg" required/>
            </div>
            <div class="f-group">
                <label class="f-label">Mot de passe initial</label>
                <input type="text" class="f-input" name="password" placeholder="À communiquer à l'employé" required/>
            </div>
            <div class="f-group">
                <label class="f-label">Département</label>
                <select class="f-select" name="departement_id" required>
                    <option value="">Sélectionner</option>
                    <?php foreach ($departements as $departement) : ?>
                        <option value="<?= esc($departement['id']) ?>"><?= esc($departement['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="f-group">
                <label class="f-label">Rôle</label>
                <select class="f-select" name="role" required>
                    <option value="employe">Employé</option>
                    <option value="rh">RH</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="f-group">
                <label class="f-label">Date d'embauche</label>
                <input type="date" class="f-input" name="date_embauche" value="<?= date('Y-m-d') ?>" required/>
            </div>
        </div>

        <div class="alert-card info" style="margin: 1.5rem 0; padding: 0.75rem 1.25rem; font-size: 0.85rem;">
            Les soldes de congés seront initialisés automatiquement selon les types de congé configurés.
        </div>

        <div class="form-actions">
            <button class="btn-forest" type="submit">Créer l'employé</button>
            <button class="btn-secondary" type="reset">Réinitialiser</button>
        </div>
    </form>
</div>

<div class="data-card">
    <div class="data-card-head">
        <h3>Tous les employes</h3>
        <form method="get" action="/admin/employes" class="form-inline">
            <input class="f-input" name="q" value="<?= esc($search ?? '') ?>" placeholder="Rechercher..."/>
            <select class="f-select" name="departement_id" onchange="this.form.submit()">
                <option value="0">Tous les depts</option>
                <?php foreach ($departements as $departement) : ?>
                    <option value="<?= esc($departement['id']) ?>" <?= (int) ($departementId ?? 0) === (int) $departement['id'] ? 'selected' : '' ?>>
                        <?= esc($departement['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
    <table class="tbl">
        <thead>
            <tr>
                <th>Employe</th>
                <th>Departement</th>
                <th>Role</th>
                <th>Embauche</th>
                <th>Statut</th>
                <th>Solde annuel</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($employes) : ?>
                <?php foreach ($employes as $employe) : ?>
                    <tr>
                        <td class="td-name">
                            <?= esc($employe['prenom'] . ' ' . $employe['nom']) ?><br/>
                            <span class="td-muted"><?= esc($employe['email']) ?></span>
                        </td>
                        <td class="td-muted"><?= esc($employe['departement_nom'] ?? '-') ?></td>
                        <td><span class="badge-soft info">employe</span></td>
                        <td class="td-mono"><?= esc($employe['date_embauche']) ?></td>
                        <td>
                            <?php if ((int) $employe['actif'] === 1) : ?>
                                <span class="badge-soft success">actif</span>
                            <?php else : ?>
                                <span class="badge-soft danger">inactif</span>
                            <?php endif; ?>
                        </td>
                        <td class="td-mono"><?= esc($employe['solde_restant']) ?> / <?= esc($employe['solde_attribues']) ?> j</td>
                        <td>
                            <div class="table-actions">
                                <a class="btn-sm btn-edit" href="/admin/employes/<?= esc($employe['id']) ?>/editer"><i class="bi bi-pencil"></i> Editer</a>
                                <?php if ((int) $employe['actif'] === 1) : ?>
                                    <form method="post" action="/admin/employes/<?= esc($employe['id']) ?>/desactiver">
                                        <?= csrf_field() ?>
                                        <button class="btn-sm btn-del" type="submit"><i class="bi bi-lock"></i> Desactiver</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="7">
                        <div class="empty">
                            <i class="bi bi-people"></i>
                            <p>Aucun employe.</p>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
