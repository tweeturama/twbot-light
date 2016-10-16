<?php

require '../config.php';

global $container;
$app = new \Slim\App($container);

$app->get('/get-followers/{username}', function ($request, $response, $args) {
    $username = $request->getAttribute('username');

    $account = \Twbot\Factory\AccountFactory::getRandomAccount();
    $twitter = \Twbot\Factory\TwitterFactory::getTwitterOAuth($account);
    $logger = \Twbot\Factory\TwitterFactory::getLogger();
    $twitterFollowService = new \Twbot\Service\TwitterFollowService($twitter, $logger);

    $followerIds = $twitterFollowService->getSeedUserFollowers($username);
    if(empty($followerIds)){
        return $response->write('No followers fetched');
    }

    /**
     * @var \Twbot\Repository\TwitterFollowRepository $twitterFollowRepository
     */
    $twitterFollowRepository = getProvider('twitterFollowRepository');
    $twitterFollowRepository->addUserIdFreeBulk($followerIds);

    return $response->write('Fetched ' . count($followerIds) . ' from ' . $username);
});

$app->get('/get-follower-info', function ($request, $response, $args) {
    /**
     * @var \Twbot\Repository\TwitterFollowRepository $twitterFollowRepository
     */
    $twitterFollowRepository = getProvider('twitterFollowRepository');

    $account = \Twbot\Factory\AccountFactory::getRandomAccount();
    $twitter = \Twbot\Factory\TwitterFactory::getTwitterOAuth($account);
    $logger = \Twbot\Factory\TwitterFactory::getLogger();
    $twitterFollowService = new \Twbot\Service\TwitterFollowService($twitter, $logger);

    $userIds = $twitterFollowRepository->getUsersWithoutInfo(1);
    $userInfos = $twitterFollowService->fetchUserInfoByUserIds($userIds);

    $twitterFollowRepository->saveUserInfos($userInfos);

    return $response->write('Follower info fetched for ' . count($userIds) . ' users');
});


$app->get('/test-follow-used', function ($request, $response, $args) {
    /**
     * @var \Twbot\Repository\TwitterFollowRepository $twitterFollowRepository
     */
    $twitterFollowRepository = getProvider('twitterFollowRepository');

    $twitterFollowRepository->addUserIdUsed('test12', '1231231');
    // prior following someone validate with seed user profile options, whether the user is of value to us
});

$app->run();