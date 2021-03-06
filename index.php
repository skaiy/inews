<?php

// Check if installed?
if (!getenv('PAGON_ENV') && is_dir(__DIR__ . '/install') && !is_file(__DIR__ . '/config/env')) {
    require(__DIR__ . '/install/index.php');
    exit;
}

/** @var $app \Pagon\App */
$app = include __DIR__ . '/app/bootstrap.php';

$app->add('Session\Cookie', array('lifetime' => 86400 * 7));
$app->add('OPAuth', array(
    'security_salt' => 'LDFmiilYf8Fyw5W10rxx4W1KsVrieQCnpBzzpTBWA5vJidQKDx8pMJbmw28R1C4m',
    'Strategy'      => $app->get('passport'),
    'callback'      => '\Route\Web\Login\Callback'
));

$app->get('/', '\Route\Web\Index');
$app->get('/latest', '\Route\Web\Latest');
$app->get('/search', '\Route\Web\Search');
$app->get('/leaders', '\Route\Web\Leader');
$app->all('/account/register', '\Route\Web\Account\Register');
$app->all('/account/login', '\Route\Web\Account\Login');
$app->all('/account/logout', '\Route\Web\Account\Logout');
$app->get('/account/verify', '\Route\Web\Account\Verify');
$app->get('/account/welcome', '\Route\Web\Account\Welcome');
$app->get('/account/resend', '\Route\Web\Account\ReSend');
$app->all('/account/edit', '\Route\Web\Account\Edit');

$app->all('/submit', '\Route\Web\Submit');
$app->get('/p/(:id)', '\Route\Web\Article');
$app->all('/p/(:id)/comment', '\Route\Web\Article\Comment');
$app->get('/p/(:id)/digg', '\Route\Web\Article\Digg');
$app->all('/p/(:id)/destroy', '\Route\Web\Article\Destroy');
$app->all('/p/(:id)/edit', '\Route\Web\Article\Edit');

$app->get('/u/(:id)', '\Route\Web\User');
$app->get('/u/(:id)/op/(:action)', '\Route\Web\UserOperate');
$app->get('/u/(:id)/posts', '\Route\Web\Account\Article');
$app->get('/u/(:id)/comments', '\Route\Web\Account\Comment');

$app->get('/my/posts', '\Route\Web\Account\Article');
$app->get('/my/diggs', '\Route\Web\Account\Digg');
$app->get('/my/comments', '\Route\Web\Account\Comment');
$app->all('/my/notice', '\Route\Web\Account\Notification');

$app->post('/api/digg', '\Route\Api\Digg');
$app->post('/api/notify/read', '\Route\Api\MarkRead');
$app->get('/api/nick', '\Route\Api\Nick');
$app->get('/api/comments', '\Route\Api\Comment');
$app->post('/api/comments', '\Route\Api\Comment');
$app->delete('/api/comments/:id', '\Route\Api\Comment');
$app->get('/api/alfred/(:type)', '\Route\Api\Alfred');
$app->all('^/api/.*$', function ($req, $res) {
    $res->json(array('error' => 1, 'message' => 'API not found'));
});

$app->get('/feed', '\Route\Service\Feed');
$app->get('/sitemap.xml', '\Route\Service\SiteMap');

$app->get('/user/(:id)', function ($req, $res) {
    $res->redirect('/u/' . $req->param('id'));
});

$app->run();