<?php

namespace App\Objects;

use App\Entity\Categories;

class SortCategoryObject
{
    private $category;
    private int $page = 1;

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    public function getCategory(): ?Categories
    {
        return $this->category;
    }

    public function setCategory(Categories $category): ?Categories
    {
        $this->category = $category;

        return $this->category;
    }
}