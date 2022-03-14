<?php

namespace App\Controller\API;

use App\Entity\Products;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProductController extends AbstractController
{
    public function __construct(
        private ManagerRegistry $manager,
        private TranslatorInterface $translator,
        private ValidatorInterface $validator
    ){}

    //API
    public function index(): JsonResponse
    {
        $products = $this->manager->getRepository(Products::class)->findAll();
        $data = [];

        foreach ($products as $product)
        {
            $data[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'description' => $product->getDescription(),
                'price' => $product->getPrice(),
                'image' => $product->getImage(),
                'isAvailable' => $product->getIsAvailable(),
                'createdAt' => $product->getCreatedAt(),
                'updatedAt' => $product->getUpdatedAt(),
                'categoryId' => $product->getCategory()->getId(),
                'orderItemsId' => $product->getOrderItems(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    public function show($id): JsonResponse
    {
        $product = $this->manager->getRepository(Products::class)->findOneBy(['id' => $id]);

        if($product == null)
        {
            return new JsonResponse($this->translator->trans('products.status.not_found', [], 'admin'), Response::HTTP_NOT_FOUND);
        }

        $data = [];

            $data[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'description' => $product->getDescription(),
                'price' => $product->getPrice(),
                'image' => $product->getImage(),
                'isAvailable' => $product->getIsAvailable(),
                'createdAt' => $product->getCreatedAt(),
                'updatedAt' => $product->getUpdatedAt(),
                'categoryId' => $product->getCategory()->getId(),
                'orderItemsId' => $product->getOrderItems(),
            ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    public function add(Request $request, SerializerInterface $serializer): JsonResponse|\Stringable|string
    {
        $data = $request->getContent();
        $product = $serializer->deserialize($data, Products::class, 'json');

        $errors = [];

        $violations = $this->validator->validate($product);

        if(!empty($violations))
        {
            foreach ($violations as $violation)
            {
                $errors[] = $violation->getMessage();
            }
            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);
        }

        $this->manager->getManager()->persist($product);
        $this->manager->getManager()->flush();

        return new JsonResponse([
            'status' => $this->translator->trans('products.status.create', [], 'admin')
        ], Response::HTTP_CREATED);
    }
}