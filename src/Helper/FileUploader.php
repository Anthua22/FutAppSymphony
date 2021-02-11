<?php

namespace App\Helper;


use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class FileUploader
{
    private $targetDir;

    public function __construct(string $targetDir)
    {
        $this->targetDir = $targetDir;
    }

    public function upload(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as parts of the URL
        $newFilename = $originalFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move(
                $this->targetDir,
                $newFilename
            );
        } catch (FileException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return $newFilename;
    }

    public function getFile(string $fileName) : File
    {
        return new File(
            $this->targetDir . '/' . $fileName
        );
    }

    public function getTargetDir() : string
    {
        return $this->targetDir;
    }
}