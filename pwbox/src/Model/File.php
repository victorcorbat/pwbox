<?php
/**
 * Created by PhpStorm.
 * User: victo
 * Date: 12/04/2018
 * Time: 18:45
 */

namespace pwbox\Model;


class File
{
    private $id;
    private $filename;
    private $folder;
    private $size;
    private $extension;

    public function __construct(
        $id,
        $filename,
        $folder,
        $size,
        $extension
    )
    {
        $this->id = $id;
        $this->filename = $filename;
        $this->folder = $folder;
        $this->size = $size;
        $this->extension = $extension;
    }

    public function getId(): string {
        return $this->id;
    }

    public function getFilename(): string {
        return $this->filename;
    }

    public function getFolder(): int {
        return $this->folder;
    }

    public function getSize(): float {
        return $this->size;
    }

    public function getExtension(): string {
        return $this->extension;
    }

}