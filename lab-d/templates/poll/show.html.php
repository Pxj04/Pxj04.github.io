<?php
/** @var \App\Model\Poll $poll */
/** @var \App\Service\Router $router */
$title = 'Podgląd ankiety';
$bodyClass = 'show';

ob_start();
?>

    <h1><?= htmlspecialchars($poll->getQuestion(), ENT_QUOTES, 'UTF-8') ?></h1>

    <p>
        <strong>Odpowiedź A:</strong>
        <?= htmlspecialchars($poll->getAnswerA(), ENT_QUOTES, 'UTF-8') ?>
    </p>

    <p>
        <strong>Odpowiedź B:</strong>
        <?= htmlspecialchars($poll->getAnswerB(), ENT_QUOTES, 'UTF-8') ?>
    </p>

    <ul class="action-list">
        <li>
            <a href="<?= $router->generatePath('poll-index') ?>">
                Powrót do listy
            </a>
        </li>
        <li>
            <a href="<?= $router->generatePath(
                'poll-edit', ['id' => $poll->getId()]
            ) ?>">Edytuj</a>
        </li>
    </ul>

<?php
$main = ob_get_clean();
include __DIR__ . '/../base.html.php';