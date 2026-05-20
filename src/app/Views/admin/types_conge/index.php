<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<div class="form-section">
    <h3>Nouveau type de conge</h3>
    <form method="post" action="/admin/types-conge/creer">
        <?= csrf_field() ?>
        <div class="form-grid-2">
            <div class="f-group">
                <label class="f-label">Libelle</label>
                <input class="f-input" name="libelle" required/>
            </div>
            <div class="f-group">
                <label class="f-label">Jours annuels</label>
                <input type="number" class="f-input" name="jours_annuels" min="1" required/>
            </div>
            <div class="f-group">
                <label class="f-label">Deductible</label>
                <select class="f-select" name="deductible" required>
                    <option value="1">Oui</option>
                    <option value="0">Non</option>
                </select>
            </div>
        </div>
        <div class="form-actions">
            <button class="btn-forest" type="submit"><i class="bi bi-check-lg"></i> Creer</button>
        </div>
    </form>
</div>

<div class="data-card">
    <div class="data-card-head">
        <h3>Types de conge</h3>
    </div>
    <table class="tbl">
        <thead>
            <tr>
                <th>Libelle</th>
                <th>Jours annuels</th>
                <th>Deductible</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($types) : ?>
                <?php foreach ($types as $type) : ?>
                    <tr>
                        <td class="td-name"><?= esc($type['libelle']) ?></td>
                        <td class="td-mono"><?= esc($type['jours_annuels']) ?></td>
                        <td class="td-muted"><?= (int) $type['deductible'] === 1 ? 'Oui' : 'Non' ?></td>
                        <td>
                            <a class="btn-sm btn-edit" href="/admin/types-conge/<?= esc($type['id']) ?>/editer"><i class="bi bi-pencil"></i> Editer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="4">
                        <div class="empty">
                            <i class="bi bi-clipboard"></i>
                            <p>Aucun type.</p>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
