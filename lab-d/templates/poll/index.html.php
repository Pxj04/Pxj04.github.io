<?php
/** @var \App\Model\Poll[] $polls */
/** @var \App\Service\Router $router */
$title = 'Ankiety';
$bodyClass = 'index';

ob_start();
?>

    <h1>Lista ankiet</h1>

    <a href="<?= $router->generatePath('poll-create') ?>">
        Dodaj ankietę
    </a>

    <ul class="index-list">
        <?php foreach ($polls as $poll): ?>
            <li>
                <h3>
                    <?= htmlspecialchars($poll->getQuestion(), ENT_QUOTES, 'UTF-8') ?>
                </h3>

                <ul class="action-list">
                    <li>
                        <a href="<?= $router->generatePath(
                            'poll-show', ['id' => $poll->getId()]
                        ) ?>">Szczegóły</a>
                    </li>
                    <li>
                        <a href="<?= $router->generatePath(
                            'poll-edit', ['id' => $poll->getId()]
                        ) ?>">Edytuj</a>
                    </li>
                </ul>
            </li>
        <?php endforeach; ?>
    </ul>

<?php
$main = ob_get_clean();
include __DIR__ . '/../base.html.php';