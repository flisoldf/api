<?php

use Controllers\AdminBotController;
use Controllers\CollaboratorsPostController;
use Controllers\ParticipantsPostController;
use Controllers\TalksPostController;
use Slim\Http\Request;
use Slim\Http\Response;

//use Controllers\ParticipantsAuthController;
//use Controllers\ParticipantsPresenceConfirmationController;
//use Controllers\ParticipantsPresenceVerifyController;
//use Controllers\ParticipantsPrizeActionController;
//use Controllers\ParticipantsPrizePickOneController;

// Routes

$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

$app->post('/participants', ParticipantsPostController::class);

$app->post('/talks', TalksPostController::class);

$app->post('/collaborators', CollaboratorsPostController::class);

$app->post('/community', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

$app->post('/adminbot', AdminBotController::class);

//$app->post('/participants/auth', ParticipantsAuthController::class);
//
//$app->post('/participants/presence/verify', ParticipantsPresenceVerifyController::class);
//
//$app->post('/participants/presence/confirmation', ParticipantsPresenceConfirmationController::class);
//
//$app->get('/participants/prize/pickone', ParticipantsPrizePickOneController::class);
//
//$app->post('/participants/prize/action', ParticipantsPrizeActionController::class);
