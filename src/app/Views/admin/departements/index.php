<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<div class="form-section">
    <h3>Nouveau departement</h3>
    <form method="post" action="/admin/departements/creer">
        <?= csrf_field() ?>
        <div class="form-grid-2">
            <div class="f-group">
                <label class="f-label">Nom</label>
                <input class="f-input" name="nom" required/>
            </div>
            <div class="f-group">
                <label class="f-label">Description</label>
                <input class="f-input" name="description"/>
            </div>
        </div>
        <div class="form-actions">
            <button class="btn-forest" type="submit"><i class="bi bi-check-lg"></i> Creer</button>
        </div>
    </form>
</div>

<div class="data-card">
    <div class="data-card-head">
        <h3>Departements</h3>
    </div>
    <table class="tbl">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($departements) : ?>
                <?php foreach ($departements as $departement) : ?>
                    <tr>
                        <td class="td-name"><?= esc($departement['nom']) ?></td>
                        <td class="td-muted"><?= esc($departement['description'] ?? '-') ?></td>
                        <td>
                            <div class="table-actions">
                                <a class="btn-sm btn-edit" href="/admin/departements/<?= esc($departement['id']) ?>/editer"><i class="bi bi-pencil"></i> Editer</a>
                                <form method="post" action="/admin/departements/<?= esc($departement['id']) ?>/supprimer">
                                    <?= csrf_field() ?>
                                    <button class="btn-sm btn-del" type="submit"><i class="bi bi-trash"></i> Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="3">
                        <div class="empty">
                            <i class="bi bi-diagram-3"></i>
                            <p>Aucun departement.</p>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
