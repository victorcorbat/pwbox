<?php
/**
 * Created by PhpStorm.
 * User: victo
 * Date: 27/04/2018
 * Time: 19:06
 */

namespace pwbox\Controller;

use Psr\Container\ContainerInterface;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


class DownloadController
{
    protected $container;

    public function __construct(ContainerInterface $container){
        $this->container = $container;
    }

    public function downloadAction(Request $request, Response $response, array $args)
    {
        $filename = $args['id'];
        // Fetch the file info.
        $filePath = __DIR__ .'/uploads/'.$filename;

        if(file_exists($filePath)) {
            $fileName = basename($filePath);
            $fileSize = filesize($filePath);

            // Output headers.
            header("Cache-Control: private");
            header("Content-Type: application/stream");
            header("Content-Length: ".$fileSize);
            header("Content-Disposition: attachment; filename=".$fileName);

            // Output file.
            readfile ($filePath);
            exit();
        }
        else {
            die('The provided file path is not valid.');
        }
    }
}

