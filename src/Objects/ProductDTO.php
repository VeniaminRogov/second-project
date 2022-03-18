<?php

namespace App\Objects;

use App\Entity\Products;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductDTO
{
    public int $id;
    public string $name;
    public ?string $description = null;
    public int $price;
    public ?string $image = null;
    public int $quantity;
    public int $category;
    public int $categoryId;
    public string $categorySlug;
    public string $isAvailable;

    const PREFIX = 'product_cache_';

    public function setCacheProduct(Products $product)
    {
        $this->id = $product->getId();
        $this->name = $product->getName();
        $this->description = $product->getDescription();
        $this->price = $product->getPrice();
        $this->image = $product->getImage();
        $this->quantity = $product->getQuantity();
    }

    public function getCacheProduct(string $json)
    {
        $product = json_decode($json);

        $this->id = $product->id;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->image = $product->image;
        $this->quantity = $product->quantity;
    }
}