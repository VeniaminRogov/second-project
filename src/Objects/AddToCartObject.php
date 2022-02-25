<?php

namespace App\Objects;

use App\Entity\Products;
use Symfony\Component\Validator\Constraints as Assert;

class AddToCartObject
{

    #[Assert\Positive]
    private int $quantity;
    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

}