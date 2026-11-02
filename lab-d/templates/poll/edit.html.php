<?php
/** @var \App\Model\Poll $poll */
/** @var \App\Service\Router $router */

$title = 'Edytuj ankietę';
$bodyClass = 'edit';

ob_start();
?>

    <h1>Edytuj ankietę</h1>

    <form
        action="<?= $router->generatePath(
            'poll-edit', ['id' => $poll->getId()]
        ) ?>"
        method="post"
        class="edit-form"
    >
        <?php require __DIR__ . '/_form.html.php'; ?>
    </form>

    <ul class="action-list">
        <li>
            <a href="<?= $router->generatePath('poll-index') ?>">
                Powrót do listy
            </a>
        </li>
        <li>
            <form
                action="<?= $router->generatePath('poll-delete') ?>"
                method="post"
            >
                <input
                    type="hidden"
                    name="id"
                    value="<?= $poll->getId() ?>"
                >
                <input
                    type="submit"
                    value="Usuń ankietę"
                    onclick="return confirm('Czy na pewno usunąć ankietę?')"
                >
            </form>
        </li>
    </ul>

<?php
$main = ob_get_clean();
include __DIR__ . '/../base.html.php';