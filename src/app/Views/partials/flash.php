<?php

$success = session()->getFlashdata('success');
$error = session()->getFlashdata('error');
$warning = session()->getFlashdata('warning');
$info = session()->getFlashdata('info');
?>

<?php if ($success) : ?>
    <div class="flash flash-success">
        <i class="bi bi-check-circle-fill"></i>
        <?= esc($success) ?>
    </div>
<?php endif; ?>

<?php if ($error) : ?>
    <div class="flash flash-error">
        <i class="bi bi-exclamation-circle-fill"></i>
        <?= esc($error) ?>
    </div>
<?php endif; ?>

<?php if ($warning) : ?>
    <div class="flash flash-warn">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <?= esc($warning) ?>
    </div>
<?php endif; ?>

<?php if ($info) : ?>
    <div class="flash flash-info">
        <i class="bi bi-info-circle-fill"></i>
        <?= esc($info) ?>
    </div>
<?php endif; ?>
