<?php

namespace App\Controller\API;

use App\Entity\Category;
use App\Entity\Products;
use App\Objects\ProductDTO;
use App\Services\UploadFileService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProductController extends AbstractController
{
    public function __construct(
        private ManagerRegistry $manager,
        private TranslatorInterface $translator,
        private ValidatorInterface $validator,
        private UploadFileService $file,
    ){}

    public function index(): JsonResponse
    {
        $products = $this->manager->getRepository(Products::class)->findAll();

        $dataProducts = [];

        foreach ($products as $product)
        {
            $dataProducts[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'description' => $product->getDescription(),
                'price' => $product->getPrice(),
                'image' => $product->getImage(),
                'quantity' => $product->getQuantity(),
                'isAvailable' => $product->getIsAvailable(),
                'createdAt' => $product->getCreatedAt(),
                'updatedAt' => $product->getUpdatedAt(),
                'category' => $product->getCategory()->getName(),
                'orderItemsId' => $product->getOrderItems(),
            ];
        }

        return new JsonResponse($dataProducts, Response::HTTP_OK);
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
                'quantity' => $product->getQuantity(),
                'category' => $product->getCategory()->getName(),
                'categoryId' => $product->getCategory()->getId(),
                'orderItemsId' => $product->getOrderItems(),
            ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    public function add(Request $request, SerializerInterface $serializer): JsonResponse
    {
        $data = $request->getContent();
        $productDTO = $serializer->deserialize($data, ProductDTO::class, 'json');
        $errors = [];
        $violations = $this->validator->validate($productDTO);
        if($violations->count() !== 0)
        {
            /** @var ConstraintViolation $violation */
            foreach ($violations as $violation)
            {
                $errors[] = [
                    'source' => $violation->getPropertyPath(),
                    'message' => $violation->getMessage()
                ];
            }
            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);
        }

        $category = $this->manager->getRepository(Category::class)->find($productDTO->category);
        $product = new Products();
        $product->setCategory($category);
        $product->setName($productDTO->name);
        $product->setDescription($productDTO->description);
        $product->setPrice($productDTO->price);
        $product->setQuantity($productDTO->quantity);
        $product->setIsAvailable($productDTO->isAvailable);
        $product->setImage($productDTO->image);
        $this->manager->getManager()->persist($product);
        $this->manager->getManager()->flush();

        return $this->show($product->getId());
    }

    public function edit(int $id, Request $request, SerializerInterface $serializer): JsonResponse
    {
        $product = $this->manager->getRepository(Products::class)->findOneBy(['id' => $id]);
        if($product == null)
        {
            return new JsonResponse($this->translator->trans('products.status.not_found', [], 'admin'), Response::HTTP_NOT_FOUND);
        }

        $data = $request->getContent();
        $productDTO = $serializer->deserialize($data, ProductDTO::class, 'json');

        $violations = $this->validator->validate($productDTO);
//        dd($violations->count());
        $errors = [];
        if($violations->count() !== 0)
        {
            /** @var ConstraintViolation $violation */
            foreach ($violations as $violation)
            {
                $errors[] = [
                    'source' => $violation->getPropertyPath(),
                    'message' =>$violation->getMessage()
                ];
            }
            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);
        }

        foreach ($productDTO as $index => $item)
        {
            if($index == 'name')
            {
                $product->setName($item);
            }
            if ($index == 'price')
            {
                $product->setPrice($item);
            }
            if ($index == 'category'){
                $category = $this->manager->getRepository(Category::class)->find($productDTO->category);
                $product->setCategory($category);
            }
            if ($index == 'description')
            {
                $product->setDescription($item);
            }
            if($index == 'image')
            {
                $product->setImage($item);
            }
            if($index == 'quantity')
            {
                $product->setQuantity($item);
            }
            if($index == 'isAvailable')
            {
                $product->setIsAvailable($item);

                if ($product->getQuantity() <= 0)
                {
                    $product->setIsAvailable(false);
                }
            }
        }

//        $product->setName($productDTO->name);

        $this->manager->getManager()->persist($product);
        $this->manager->getManager()->flush();

        return new JsonResponse([
            'status' => $this->translator->trans('products.status.update', [], 'admin')
        ], Response::HTTP_OK);
    }

    public function delete($id): JsonResponse
    {
        $product = $this->manager->getRepository(Products::class)->find($id);
        if(!$product)
        {
            return new JsonResponse($this->translator->trans('products.status.not_found', [], 'admin'), Response::HTTP_NOT_FOUND);
        }

        $this->manager->getManager()->remove($product);
        $this->manager->getManager()->flush();
        return new JsonResponse([
            'status' => $this->translator->trans('products.status.delete',[],'admin')
        ], Response::HTTP_OK);
    }
}