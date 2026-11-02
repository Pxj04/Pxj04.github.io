<?php
/** @var \App\Model\Poll $poll */
/** @var \App\Service\Router $router */

$title = 'Dodaj ankietę';
$bodyClass = 'edit';

ob_start();
?>

    <h1>Dodaj ankietę</h1>

    <form
        action="<?= $router->generatePath('poll-create') ?>"
        method="post"
        class="edit-form"
    >
        <?php require __DIR__ . '/_form.html.php'; ?>
    </form>

    <a href="<?= $router->generatePath('poll-index') ?>">
        Powrót do listy
    </a>

<?php
$main = ob_get_clean();
include __DIR__ . '/../base.html.php';