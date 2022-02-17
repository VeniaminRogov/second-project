<?php

namespace App\Services;

use App\Entity\Products;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductsService
{
    private $doctrine;
    public function __construct(
        ManagerRegistry $doctrine,
        private $targetDirectory,
        private SluggerInterface $slugger
    )
    {
        $this->doctrine = $doctrine->getManager();
    }

    public function checkProductId(?int $id = null)
    {
        if(!$id){
            return $id;
        }
        return $this->doctrine->getRepository(Products::class)->find($id);
    }

    public function createAndUpdate(Products $products, $image): Products
    {

        $products->setImage($this->uploadsImage($products, $image));

        $this->doctrine->persist($products);
        $this->doctrine->flush();


        return $products;
    }

    private function getTargetDirectory(){
        return $this->targetDirectory;
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

    public function delete(Products $products): bool
    {
        $this->doctrine->remove($products);
        $this->doctrine->flush();

        return true;
    }
}