<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class UploadFileService
{
    public function __construct(
        private $targetDirectory,
        private SluggerInterface $slugger,
        private ImagineService $imagine,
    ){}

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

    public function getFileName(UploadedFile $image): string
    {
        $originalFileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFileName = $this->slugger->slug($originalFileName);
        return $safeFileName.'-'.uniqid().'.'.$image->guessExtension();
    }

    public function uploadFile(UploadedFile $image): string
    {
        $fileName = $this->getFileName($image);

        try {
            $image->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e){
            return 'Message: '.$e->getMessage();
        }

        $filePath = '/uploads/images/'.$fileName;

        $filteredImagePath = $this->imagine->setImagine($filePath);

        return $fileName;
    }
}