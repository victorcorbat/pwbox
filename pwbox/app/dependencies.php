<?php

$container = $app->getContainer();

$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig('../src/view/templates', [
    ]);

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '',
        $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(
        new \Slim\Views\TwigExtension(
            $container['router'], $basePath
        )
    );

    return $view;
};

$container['doctrine'] = function($container){
    $config = new \Doctrine\DBAL\Configuration();
    $connection = \Doctrine\DBAL\DriverManager::getConnection(
        $container->get('settings')['database'],
        $config
    );
    return $connection;
};

$container['user_repository'] = function($container){
    $repository = new \pwbox\Model\Implementation\DoctrineUserRepository(
        $container->get('doctrine') //conexión
    );
    return $repository;
};

$container['post_user_use_case'] = function($container){
    $useCase = new pwbox\Model\UseCase\PostUserUseCase(
        $container->get('user_repository') //repositorio
    );
    return $useCase;
};

$container['login_user_service'] = function($container){
    $useCase = new pwbox\Model\UseCase\LoginUserService(
        $container->get('user_repository') //repositorio
    );
    return $useCase;
};

$container['folders_user_service'] = function($container){
    $useCase = new pwbox\Model\UseCase\FoldersUserService(
        $container->get('user_repository') //repositorio
    );
    return $useCase;
};

$container['folders_inside_service'] = function($container){
    $useCase = new pwbox\Model\UseCase\FoldersInsideService(
        $container->get('user_repository') //repositorio
    );
    return $useCase;
};

$container['files_inside_service'] = function($container){
    $useCase = new pwbox\Model\UseCase\FilesInsideService(
        $container->get('user_repository') //repositorio
    );
    return $useCase;
};

$container['user_service'] = function($container){
    $useCase = new pwbox\Model\UseCase\UserService(
        $container->get('user_repository') //repositorio
    );
    return $useCase;
};

$container['update_service'] = function($container){
    $useCase = new pwbox\Model\UseCase\UpdateService(
        $container->get('user_repository') //repositorio
    );
    return $useCase;
};

$container['add_folder_service'] = function($container){
    $useCase = new pwbox\Model\UseCase\AddFolderService(
        $container->get('user_repository') //repositorio
    );
    return $useCase;
};

$container['rename_folder_service'] = function($container){
    $useCase = new pwbox\Model\UseCase\RenameFolderService(
        $container->get('user_repository') //repositorio
    );
    return $useCase;
};

$container['rename_file_service'] = function($container){
    $useCase = new pwbox\Model\UseCase\RenameFileService(
        $container->get('user_repository') //repositorio
    );
    return $useCase;
};

$container['remove_folder_service'] = function($container){
    $useCase = new pwbox\Model\UseCase\RemoveFolderService(
        $container->get('user_repository') //repositorio
    );
    return $useCase;
};

$container['remove_file_service'] = function($container){
    $useCase = new pwbox\Model\UseCase\RemoveFileService(
        $container->get('user_repository') //repositorio
    );
    return $useCase;
};

$container['remove_user_service'] = function($container){
    $useCase = new pwbox\Model\UseCase\RemoveUserService(
        $container->get('user_repository') //repositorio
    );
    return $useCase;
};

$container['get_parent_service'] = function($container){
    $useCase = new pwbox\Model\UseCase\GetParentService(
        $container->get('user_repository') //repositorio
    );
    return $useCase;
};

$container['get_folder_service'] = function($container){
    $useCase = new pwbox\Model\UseCase\GetFolderService(
        $container->get('user_repository') //repositorio
    );
    return $useCase;
};

$container['update_storage_service'] = function($container){
    $useCase = new pwbox\Model\UseCase\UpdateStorageService(
        $container->get('user_repository') //repositorio
    );
    return $useCase;
};

$container['folder_name_service'] = function($container){
    $useCase = new pwbox\Model\UseCase\FolderNameService(
        $container->get('user_repository') //repositorio
    );
    return $useCase;
};

$container['shared_folder_service'] = function($container){
    $useCase = new pwbox\Model\UseCase\SharedFolderService(
        $container->get('user_repository') //repositorio
    );
    return $useCase;
};

$container['upload_service'] = function($container){
    $useCase = new pwbox\Model\UseCase\UploadService(
        $container->get('user_repository') //repositorio
    );
    return $useCase;
};

$container['shared_file_service'] = function($container){
    $useCase = new pwbox\Model\UseCase\SharedFileService(
        $container->get('user_repository') //repositorio
    );
    return $useCase;
};

$container['share_service'] = function($container){
    $useCase = new pwbox\Model\UseCase\ShareService(
        $container->get('user_repository') //repositorio
    );
    return $useCase;
};

$container['creator_service'] = function($container){
    $useCase = new pwbox\Model\UseCase\CreatorService(
        $container->get('user_repository') //repositorio
    );
    return $useCase;
};

$container['file_creator_service'] = function($container){
    $useCase = new pwbox\Model\UseCase\FileCreatorService(
        $container->get('user_repository') //repositorio
    );
    return $useCase;
};

$container['chain_service'] = function($container){
    $useCase = new pwbox\Model\UseCase\ChainService(
        $container->get('user_repository') //repositorio
    );
    return $useCase;
};

$container['accessible_service'] = function($container){
    $useCase = new pwbox\Model\UseCase\AccessibleService(
        $container->get('user_repository') //repositorio
    );
    return $useCase;
};


$container['flash'] = function($container){
    return new \Slim\Flash\Messages();
};

/**
 * Created by PhpStorm.
 * User: victo
 * Date: 05/04/2018
 * Time: 19:32
 */