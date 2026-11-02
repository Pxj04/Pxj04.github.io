<?php

namespace App\Controller;

use App\Exception\NotFoundException;
use App\Model\Poll;
use App\Service\Router;
use App\Service\Templating;

class PollController
{
    public function indexAction(Templating $templating, Router $router): ?string
    {
        return $templating->render('poll/index.html.php', [
            'polls' => Poll::findAll(),
            'router' => $router,
        ]);
    }

    public function showAction(
        int $pollId,
        Templating $templating,
        Router $router
    ): ?string {
        $poll = Poll::find($pollId);

        if (!$poll) {
            throw new NotFoundException("Brak ankiety o ID $pollId");
        }

        return $templating->render('poll/show.html.php', [
            'poll' => $poll,
            'router' => $router,
        ]);
    }

    public function createAction(
        ?array $requestPoll,
        Templating $templating,
        Router $router
    ): ?string {
        $poll = new Poll();

        if ($requestPoll) {
            $poll->fill($requestPoll);
            $poll->save();

            $router->redirect($router->generatePath('poll-index'));
            return null;
        }

        return $templating->render('poll/create.html.php', [
            'poll' => $poll,
            'router' => $router,
        ]);
    }

    public function editAction(
        int $pollId,
        ?array $requestPoll,
        Templating $templating,
        Router $router
    ): ?string {
        $poll = Poll::find($pollId);

        if (!$poll) {
            throw new NotFoundException("Brak ankiety o ID $pollId");
        }

        if ($requestPoll) {
            $poll->fill($requestPoll);
            $poll->save();

            $router->redirect($router->generatePath('poll-index'));
            return null;
        }

        return $templating->render('poll/edit.html.php', [
            'poll' => $poll,
            'router' => $router,
        ]);
    }

    public function deleteAction(int $pollId, Router $router): ?string
    {
        $poll = Poll::find($pollId);

        if (!$poll) {
            throw new NotFoundException("Brak ankiety o ID $pollId");
        }

        $poll->delete();
        $router->redirect($router->generatePath('poll-index'));

        return null;
    }
}