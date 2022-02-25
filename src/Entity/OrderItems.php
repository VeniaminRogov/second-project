<?php

namespace App\Entity;

use App\Repository\OrderItemsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderItemsRepository::class)]
class OrderItems
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\OneToMany(mappedBy: 'orderItems', targetEntity: Products::class)]
    private $product;

    #[ORM\ManyToOne(targetEntity: Orders::class, inversedBy: 'orderItems')]
    private $order_id;

    #[ORM\Column(type: 'integer')]
    private $count;

    public function __construct()
    {
        $this->product = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Products[]
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }

    public function addProduct(Products $product): self
    {
        if (!$this->product->contains($product)) {
            $this->product[] = $product;
            $product->setOrderItems($this);
        }

        return $this;
    }

    public function removeProduct(Products $product): self
    {
        if ($this->product->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getOrderItems() === $this) {
                $product->setOrderItems(null);
            }
        }

        return $this;
    }

    public function getOrderId(): ?Orders
    {
        return $this->order_id;
    }

    public function setOrderId(?Orders $order_id): self
    {
        $this->order_id = $order_id;

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(int $count): self
    {
        $this->count = $count;

        return $this;
    }
}
