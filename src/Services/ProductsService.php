<?php

namespace App\Services;

use App\Entity\Category;
use App\Entity\Merchandise;
use App\Entity\Products;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductsService
{
    private $doctrine;
    public function __construct(
        ManagerRegistry $doctrine,
        private $targetDirectory,
        private SluggerInterface $slugger,
        private $projectDir,
        private FlashService $flashService
    )
    {
        $this->doctrine = $doctrine->getManager();
    }

    public function getProductById(?int $id = null)
    {
        if(!$id){
            return $id;
        }
        return $this->doctrine->getRepository(Merchandise::class)->find($id);
    }

    public function createAndUpdate(?int $id, Products $products, $image): Products
    {
        $products->setImage($this->uploadsImage($products, $image));

        $products->setIsAvailable(true);

        if($products->getQuantity() <= 0)
        {
            $products->setIsAvailable(false);
        }

        $this->doctrine->persist($products);
        $this->doctrine->flush();

        $this->flashService->onCreateUpdateProduct($id);

        return $products;
    }

    private function getTargetDirectory(){
        return $this->targetDirectory;
    }

    public function delete(Merchandise $products): bool
    {
        $this->doctrine->remove($products);
        $this->doctrine->flush();
        $this->flashService->onDeleteProduct();
        return true;
    }

    private function getProjectDir(){
        return $this->projectDir;
    }

    private function uploadsImage(Products $products, ?UploadedFile $image): ?string
    {
        if($image == null){
            return $products->getImage();
        }
        try {
            unlink($this->getTargetDirectory().''.$products->getImage());
        } catch (\Exception $e){}

        $originalFileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFileName = $this->slugger->slug($originalFileName);
        $fileName = $safeFileName.'-'.uniqid().'.'.$image->guessExtension();
        try {
            $image->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e){
        }

        return $fileName;
    }

    public function importFromCsv(?UploadedFile $csv)
    {
        $inputFileName = pathinfo($csv->getClientOriginalName(), PATHINFO_FILENAME);
        $inputFile = $this->projectDir.'/public/import-products/'.$inputFileName.'.csv';

        $decoder = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);

        $importProducts = $decoder->decode(file_get_contents($inputFile), 'csv');

        $products = $this->doctrine->getRepository(Products::class);

        foreach ($importProducts as $importProduct)
        {
            if($existingItem = $products->findOneBy(['name' => $importProduct['name']]))
            {
                $existingItem->setName($importProduct['name']);
                $existingItem->setDescription($importProduct['description']);
                $existingItem->setPrice($importProduct['price']);
                $existingItem->setQuantity($importProduct['quantity']);
                $existingItem->setIsAvailable($importProduct['is_available']);

                $this->doctrine->persist($existingItem);

                continue;
            }
            $product = new Products();
            $product->setName($importProduct['name']);
            $product->setDescription($importProduct['description']);
            $product->setPrice($importProduct['price']);
            $product->setQuantity($importProduct['quantity']);
            $product->setIsAvailable($importProduct['is_available']);

            $categories = new Category();
            $categoryId = $importProduct['category'];
            $categoryRepo = $this->doctrine->getRepository(Category::class)->findBy(['Name' => $categoryId]);

            if ($categoryRepo)
            {
                foreach ($categoryRepo as $item)
                {
                    $categories = $item->addProduct($product);
                }
                $this->doctrine->persist($categories);
            }
            $categoryName = $categories->setName($importProduct['category']);
            $product->setCategory($categoryName);

            $this->doctrine->persist($product);
        }
        $this->doctrine->flush();
        return true;
    }
}