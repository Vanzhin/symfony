<?php

namespace App\Service;

use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{

    private SluggerInterface $slugger;
    private Filesystem $articleFileSystem;

    public function __construct(SluggerInterface $slugger, Filesystem $articleFileSystem)
    {
        $this->slugger = $slugger;
        $this->articleFileSystem = $articleFileSystem;
    }

    /**
     * @throws FilesystemException
     */
    public function uploadImage(File $file, string $oldFileName = null): string
    {
        $fileName = $this->slugger
            ->slug(pathinfo($file instanceof UploadedFile ? $file->getClientOriginalName() : $file->getFilename(), PATHINFO_FILENAME))
            ->append('_' . (new \DateTime())->getTimestamp())
            ->append('.' . $file->guessExtension())
            ->toString();

        $this->upload($file, $fileName);
        $this->delete($oldFileName);


        return $fileName;
    }

    /**
     * @throws FilesystemException
     * @throws \Exception
     */
    public function upload(File $file, string $fileName): void
    {
        $stream = fopen($file->getPathname(), 'r');

        $result = $this->articleFileSystem->writeStream($fileName, $stream);
        if (is_resource($stream)) {
            fclose($stream);
        }

        if ($result) {
            throw new \Exception("Не удалось загрузить файл: $fileName");

        }
    }

    /**
     * @throws FilesystemException
     * @throws \Exception
     */
    public function delete(?string $fileName): void
    {
        if ($fileName && $this->articleFileSystem->has($fileName)) {
            $result = $this->articleFileSystem->delete($fileName);
            if ($result) {
                throw new \Exception("Не удалось удалить файл: $fileName");
            }
        }
    }
}