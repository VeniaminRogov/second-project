<?php

namespace App\Objects;

use App\Entity\Products;

class ProductDTO
{
    public int $id;
    public string $name;
    public string $description;
    public int $price;
    public string $image;
    public int $quantity;

    const PREFIX = 'product_cache_';


    public function setProduct(Products $product)
    {
        $this->id = $product->getId();
        $this->name = $product->getName();
        $this->description = $product->getDescription();
        $this->price = $product->getPrice();
        $this->image = $product->getImage();
        $this->quantity = $product->getQuantity();
    }

    public function getProduct(string $json)
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