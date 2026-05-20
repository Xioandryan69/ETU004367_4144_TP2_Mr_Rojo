<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<div class="form-section">
    <h3>Editer type de conge</h3>
    <form method="post" action="/admin/types-conge/<?= esc($type['id']) ?>/editer">
        <?= csrf_field() ?>
        <div class="form-grid-2">
            <div class="f-group">
                <label class="f-label">Libelle</label>
                <input class="f-input" name="libelle" value="<?= esc($type['libelle']) ?>" required/>
            </div>
            <div class="f-group">
                <label class="f-label">Jours annuels</label>
                <input type="number" class="f-input" name="jours_annuels" value="<?= esc($type['jours_annuels']) ?>" min="1" required/>
            </div>
            <div class="f-group">
                <label class="f-label">Deductible</label>
                <select class="f-select" name="deductible" required>
                    <option value="1" <?= (int) $type['deductible'] === 1 ? 'selected' : '' ?>>Oui</option>
                    <option value="0" <?= (int) $type['deductible'] === 0 ? 'selected' : '' ?>>Non</option>
                </select>
            </div>
        </div>
        <div class="form-actions">
            <button class="btn-forest" type="submit"><i class="bi bi-check-lg"></i> Enregistrer</button>
            <a class="btn-secondary" href="/admin/types-conge"><i class="bi bi-arrow-left"></i> Retour</a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
