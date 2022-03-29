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

    #[ORM\OneToMany(mappedBy: 'orderItems', targetEntity: Merchandise::class)]
    private $product;

    #[ORM\ManyToOne(targetEntity: Orders::class, cascade: ['persist'], inversedBy: 'orderItems')]
    private $orderId;

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
     * @return Collection|Merchandise[]
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }

    public function addProduct(Merchandise $product): self
    {
        if (!$this->product->contains($product)) {
            $this->product[] = $product;
            $product->setOrderItems($this);
        }

        return $this;
    }

    public function removeProduct(Merchandise $product): self
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
        return $this->orderId;
    }

    public function setOrderId(?Orders $orderId): self
    {
        $this->orderId = $orderId;

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
