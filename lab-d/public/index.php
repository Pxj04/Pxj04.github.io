<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'autoload.php';

$config = new \App\Service\Config();

$templating = new \App\Service\Templating();
$router = new \App\Service\Router();

$action = $_REQUEST['action'] ?? null;
switch ($action) {
    case 'post-index':
    case null:
        $controller = new \App\Controller\PostController();
        $view = $controller->indexAction($templating, $router);
        break;
    case 'post-create':
        $controller = new \App\Controller\PostController();
        $view = $controller->createAction($_REQUEST['post'] ?? null, $templating, $router);
        break;
    case 'post-edit':
        if (! $_REQUEST['id']) {
            break;
        }
        $controller = new \App\Controller\PostController();
        $view = $controller->editAction($_REQUEST['id'], $_REQUEST['post'] ?? null, $templating, $router);
        break;
    case 'post-show':
        if (! $_REQUEST['id']) {
            break;
        }
        $controller = new \App\Controller\PostController();
        $view = $controller->showAction($_REQUEST['id'], $templating, $router);
        break;
    case 'post-delete':
        if (! $_REQUEST['id']) {
            break;
        }
        $controller = new \App\Controller\PostController();
        $view = $controller->deleteAction($_REQUEST['id'], $router);
        break;
    case 'poll-index':
        $controller = new \App\Controller\PollController();
        $view = $controller->indexAction($templating, $router);
        break;

    case 'poll-show':
        $controller = new \App\Controller\PollController();
        $view = $controller->showAction(
            (int) ($_GET['id'] ?? 0),
            $templating,
            $router
        );
        break;

    case 'poll-create':
        $controller = new \App\Controller\PollController();
        $view = $controller->createAction(
            $_POST['poll'] ?? null,
            $templating,
            $router
        );
        break;

    case 'poll-edit':
        $controller = new \App\Controller\PollController();
        $view = $controller->editAction(
            (int) ($_GET['id'] ?? 0),
            $_POST['poll'] ?? null,
            $templating,
            $router
        );
        break;

    case 'poll-delete':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            $view = 'Usuwanie wymaga formularza POST.';
            break;
        }

        $controller = new \App\Controller\PollController();
        $view = $controller->deleteAction(
            (int) ($_POST['id'] ?? 0),
            $router
        );
        break;
    case 'info':
        $controller = new \App\Controller\InfoController();
        $view = $controller->infoAction();
        break;
    default:
        $view = 'Not found';
        break;
}

if ($view) {
    echo $view;
}
