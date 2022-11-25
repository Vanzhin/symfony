<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{

    private SluggerInterface $slugger;
    private string $uploadPath;

    public function __construct(SluggerInterface $slugger, string $uploadPath)
    {
        $this->slugger = $slugger;
        $this->uploadPath = $uploadPath;
    }

    public function uploadImage(File $image): string
    {
        $fileName = $this->slugger
            ->slug(pathinfo($image instanceof UploadedFile ? $image->getClientOriginalName() : $image->getFilename(), PATHINFO_FILENAME))
            ->append('_' . (new \DateTime())->getTimestamp())
            ->append('.' . $image->guessExtension())
            ->toString();

        $image->move($this->uploadPath, $fileName);
        return $fileName;
    }
}