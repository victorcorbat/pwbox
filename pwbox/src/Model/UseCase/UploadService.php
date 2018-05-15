<?php
/**
 * Created by PhpStorm.
 * User: victo
 * Date: 02/05/2018
 * Time: 18:58
 */
namespace pwbox\Model\UseCase;

use pwbox\Model\UserRepository;
use pwbox\Model\File;


class UploadService{
    private $repository;

    public function __construct(UserRepository $repository){
        $this->repository = $repository;
    }

    public function __invoke(array $rawData)
    {
        $file = new File(
            $rawData['basename'],
            $rawData['filename'],
            $rawData['folder'],
            $rawData['size'],
            $rawData['extension']
        );
        $this->repository->addFile($file);
    }
}