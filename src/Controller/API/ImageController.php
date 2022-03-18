<?php

namespace App\Controller\API;

use App\Services\ImagineService;
use App\Services\UploadFileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ImageController extends AbstractController
{

    public function __construct(
        private UploadFileService $file,
    ){}

    public function create(Request $request):JsonResponse
    {
        $image = $request->files->get('image');

        $fileName = $this->file->uploadFile($image);

        return new JsonResponse(
            $fileName,
            Response::HTTP_CREATED
        );
    }

//    public getImage()
}