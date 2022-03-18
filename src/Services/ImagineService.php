<?php

namespace App\Services;

use Liip\ImagineBundle\Service\FilterService;

class ImagineService
{
    public function __construct(
        private FilterService $filter,
    )
    {}

    public function setImagine(string $path): array
    {
        $filters = [];

        $filters[] = [
            'min_thumb' => $this->filter->getUrlOfFilteredImage($path, 'min_thumb'),
            'medium_thumb' => $this->filter->getUrlOfFilteredImage($path, 'medium_thumb'),
            'large_thumb' => $this->filter->getUrlOfFilteredImage($path, 'large_thumb')
        ];

        return $filters;
    }
}