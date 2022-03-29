<?php

namespace App\Controller\API;

use App\Entity\Category;
use App\Entity\Service;
use App\Objects\ProductDTO;
use App\Objects\ServiceDTO;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ServiceController extends AbstractController
{
    public function __construct(
        private ManagerRegistry $manager,
        private TranslatorInterface $translator,
        private ValidatorInterface $validator,
    ){}

    public function index(): JsonResponse
    {
        $services = $this->manager->getRepository('App:Service')->findAll();
        $data = [];

        foreach ($services as $service)
        {
            $data[] = [
                'id' => $service->getId(),
                'name' => $service->getName(),
                'description' => $service->getDescription(),
                'price' => $service->getPrice(),
                'time' => $service->getTime(),
                'isAvailable' => $service->getIsAvailable(),
                'createdAt' => $service->getCreatedAt(),
                'updatedAt' => $service->getUpdatedAt(),
                'category' => $service->getCategory()->getName(),
                'orderItemsId' => $service->getOrderItems(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    public function show(int $id): JsonResponse
    {
        $service = $this->manager->getRepository("App:Service")->findOneBy(['id' => $id]);

        if($service == null)
        {
            return new JsonResponse($this->translator->trans('products.status.not_found', [], 'admin'), Response::HTTP_NOT_FOUND);
        }

        $data[] = [
            'id' => $service->getId(),
            'name' => $service->getName(),
            'description' => $service->getDescription(),
            'price' => $service->getPrice(),
            'isAvailable' => $service->getIsAvailable(),
            'createdAt' => $service->getCreatedAt(),
            'updatedAt' => $service->getUpdatedAt(),
            'quantity' => $service->getTime(),
            'category' => $service->getCategory()->getName(),
            'categoryId' => $service->getCategory()->getId(),
            'orderItemsId' => $service->getOrderItems(),
        ];

        return new JsonResponse($data, Response::HTTP_CREATED);
    }

    public function add(Request $request, SerializerInterface $serializer): JsonResponse
    {
        $data = $request->getContent();
        $serviceDTO = $serializer->deserialize($data, ServiceDTO::class, 'json');
//        dd($productDTO);
        $errors = [];
        $violations = $this->validator->validate($serviceDTO);
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

        $category = $this->manager->getRepository(Category::class)->find($serviceDTO->category);
//        $image = $this->manager->getRepository(Image::class)->findOneBy(['name' => $productDTO->image]);

            $service = new Service();
            $service->setCategory($category);
            $service->setName($serviceDTO->name);
            $service->setDescription($serviceDTO->description);
            $service->setPrice($serviceDTO->price);
            $service->setTime($serviceDTO->time);
            $service->setIsAvailable($serviceDTO->isAvailable);

            $this->manager->getManager()->persist($service);
            $this->manager->getManager()->flush();

            return $this->show($service->getId());
    }

    public function edit(int $id, Request $request, SerializerInterface $serializer): JsonResponse
    {
        $service = $this->manager->getRepository("App:Service")->findOneBy(['id' => $id]);
        if($service == null)
        {
            return new JsonResponse($this->translator->trans('products.status.not_found', [], 'admin'), Response::HTTP_NOT_FOUND);
        }

        $data = $request->getContent();

        $serviceDTO = $serializer->deserialize($data, ServiceDTO::class, 'json');

        $violations = $this->validator->validate($serviceDTO);

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

        foreach ($serviceDTO as $index => $item)
        {
            if($index == 'name')
            {
                $service->setName($item);
            }
            if ($index == 'price')
            {
                $service->setPrice($item);
            }
            if ($index == 'category'){
                $category = $this->manager->getRepository(Category::class)->find($productDTO->category);
                $service->setCategory($category);
            }
            if ($index == 'description')
            {
                $service->setDescription($item);
            }
            if($index == 'quantity')
            {
                $service->setQuantity($item);
            }
            if($index == 'isAvailable')
            {
                $service->setIsAvailable($item);

                if ($service->getQuantity() <= 0)
                {
                    $service->setIsAvailable(false);
                }
            }
        }

//        $product->setName($productDTO->name);

        $this->manager->getManager()->persist($service);
        $this->manager->getManager()->flush();

        return new JsonResponse([
            'status' => $this->translator->trans('products.status.update', [], 'admin')
        ], Response::HTTP_OK);
    }

    public function delete($id): JsonResponse
    {
        $service = $this->manager->getRepository("App:Service")->find($id);
        if(!$service)
        {
            return new JsonResponse($this->translator->trans('products.status.not_found', [], 'admin'), Response::HTTP_NOT_FOUND);
        }

        $this->manager->getManager()->remove($service);
        $this->manager->getManager()->flush();

        return new JsonResponse([
            'status' => $this->translator->trans('products.status.delete',[],'admin')
        ], Response::HTTP_OK);
    }
}