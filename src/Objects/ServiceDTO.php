<?php

namespace App\Objects;

class ServiceDTO
{
    public int $id;
    public string $name;
    public ?string $description = null;
    public int $price;
//    public ?string $image = null;
    public ?int $time = null;
    public int $category;
    public string $categorySlug;
    public string $isAvailable;
}