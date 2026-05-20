<?= $this->extend('layout/app') ?>

<?= $this->section('content') ?>
<div class="form-section">
    <h3>Editer departement</h3>
    <form method="post" action="/admin/departements/<?= esc($departement['id']) ?>/editer">
        <?= csrf_field() ?>
        <div class="form-grid-2">
            <div class="f-group">
                <label class="f-label">Nom</label>
                <input class="f-input" name="nom" value="<?= esc($departement['nom']) ?>" required/>
            </div>
            <div class="f-group">
                <label class="f-label">Description</label>
                <input class="f-input" name="description" value="<?= esc($departement['description'] ?? '') ?>"/>
            </div>
        </div>
        <div class="form-actions">
            <button class="btn-forest" type="submit"><i class="bi bi-check-lg"></i> Enregistrer</button>
            <a class="btn-secondary" href="/admin/departements"><i class="bi bi-arrow-left"></i> Retour</a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
