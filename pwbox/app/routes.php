<?php
/*

*/


$app->add('pwbox\Controller\Middleware\SessionMiddleware');

$app->get(
    '/',
    'pwbox\Controller\LoginController:indexAction'
);

$app->post(
    '/logout',
    'pwbox\Controller\LoginController:logoutAction'
);

$app->post(
    '/',
    'pwbox\Controller\LoginController:loginAction'
);

$app->get(
    '/register',
    'pwbox\Controller\RegisterController:indexAction'
);

$app->post(
    '/register',
    'pwbox\Controller\RegisterController:registerAction'
);

$app->get(
    '/dashboard[/{folder_id}]',
    'pwbox\Controller\DashboardController:indexAction'
);

$app->get(
    '/shared[/{folder_id}]',
    'pwbox\Controller\SharedController:indexAction'
);


$app->get(
    '/profile',
    'pwbox\Controller\ProfileController:indexAction'
);

$app->post(
    '/profile',
    'pwbox\Controller\ProfileController:updateAction'
);

$app->post(
    '/crear[/{folder_id}]',
    'pwbox\Controller\FolderController:createAction'
);

$app->post(
    '/rename[/{folder_id}]',
    'pwbox\Controller\FolderController:renameAction'
);

$app->post(
    '/remove_folder[/{folder_id}]',
    'pwbox\Controller\FolderController:removeAction'
);

$app->post(
    '/remove_file[/{file_id}]',
    'pwbox\Controller\FileController:removeAction'
);

$app->post(
    '/rename_file[/{file_id}]',
    'pwbox\Controller\FileController:renameAction'
);

$app->post(
    '/remove_user[/{user_id}]',
    'pwbox\Controller\ProfileController:removeAction'
);

$app->post(
    '/upload[/{folder_id}]',
    'pwbox\Controller\UploadController:uploadAction'
);

$app->get(
    '/download[/{id}]',
    'pwbox\Controller\DownloadController:downloadAction'
);


$app->post(
    '/share[/{folder_id}]',
    'pwbox\Controller\ShareController:shareAction'
);


//otras

$app->get(
    '/user',
    'pwbox\Controller\PostUserController:indexAction'
);


$app->post(
    '/user',
    'pwbox\Controller\PostUserController:registerAction'
);

$app->get(
    '/hello/{name}',
    'pwbox\Controller\HelloController'
)->add('pwbox\Controller\Middleware\UserLoggerMiddleware');
