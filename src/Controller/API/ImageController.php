<?php

namespace App\Controller\API;

use App\Entity\Image;
use App\Repository\ImageRepository;
use App\Services\ImagineService;
use App\Services\UploadFileService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ImageController extends AbstractController
{


    public function __construct(
        private UploadFileService $file,
        private ManagerRegistry $manager,
        private ImagineService $imagine,
    ){}

    public function create(Request $request):JsonResponse
    {
        $file = $request->files->get('image');
        $fileName = $this->file->uploadFile($file);

        if (!$this->manager->getRepository(Image::class)->findOneBy(['name' => $fileName]))
        {
            $image = new Image();
            $image->setName($fileName);

            $this->manager->getManager()->persist($image);
            $this->manager->getManager()->flush();
        }

        $filePath = '/uploads/images/'.$fileName;

        $filteredImagePath = $this->imagine->setImagine($filePath);

        return new JsonResponse(
            ['imgName' => $fileName,'filteredImg' => $filteredImagePath],
            Response::HTTP_CREATED
        );
    }

//    public getImage()
}