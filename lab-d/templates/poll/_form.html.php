<?php
/** @var \App\Model\Poll $poll */
?>

<div>
    <label for="question">Pytanie:</label>
    <input
        type="text"
        id="question"
        name="poll[question]"
        value="<?= htmlspecialchars($poll->getQuestion(), ENT_QUOTES, 'UTF-8') ?>"
        required
    >
</div>

<div>
    <label for="answer_a">Odpowiedź A:</label>
    <input
        type="text"
        id="answer_a"
        name="poll[answer_a]"
        value="<?= htmlspecialchars($poll->getAnswerA(), ENT_QUOTES, 'UTF-8') ?>"
        required
    >
</div>

<div>
    <label for="answer_b">Odpowiedź B:</label>
    <input
        type="text"
        id="answer_b"
        name="poll[answer_b]"
        value="<?= htmlspecialchars($poll->getAnswerB(), ENT_QUOTES, 'UTF-8') ?>"
        required
    >
</div>

<div>
    <input type="submit" value="Zapisz">
</div>