<?php

namespace App\Entity;

use App\Repository\ProductsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

//#[ORM\Entity(repositoryClass: MerchandisesRepository::class)]
#[ORM\Entity]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'discr', type: 'string')]
#[ORM\DiscriminatorMap(["merchandise" => "Merchandise", "service" => "Service", "product" => "Product"])]
#[ORM\Table(name: 'merchandises')]
abstract class Merchandise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $description;

    #[ORM\Column(type: 'integer')]
    private $price;

    #[ORM\Column(type: 'boolean')]
    private $isAvailable;

    #[ORM\Column(type: "datetime")]
    #[Gedmo\Timestampable(on: 'create')]
    private $createdAt;

    /**
     * @var $updatedAt
     */
    #[ORM\Column(type: "datetime")]
    #[Gedmo\Timestampable()]
    private $updatedAt;

    #[ORM\ManyToOne(targetEntity: Category::class, cascade: ['persist'], inversedBy: 'products')]
    #[ORM\JoinColumn(name: "category_id", referencedColumnName: 'id', onDelete: "SET NULL")]
    private $category;

    #[ORM\ManyToOne(targetEntity: OrderItems::class, inversedBy: 'product')]
    private $orderItems;
//
//    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Image::class, cascade: ['persist'])]
//    #[ORM\JoinColumn(name: 'product_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
//    private $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getIsAvailable(): ?bool
    {
        return $this->isAvailable;
    }

    public function setIsAvailable(bool $isAvailable): self
    {
        $this->isAvailable = $isAvailable;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrderItems()
    {
        return $this->orderItems;
    }

    /**
     * @param mixed $orderItems
     */
    public function setOrderItems($orderItems): void
    {
        $this->orderItems = $orderItems;
    }

//    /**
//     * @return Collection<int, Image>
//     */
//    public function getImages(): Collection
//    {
//        return $this->images;
//    }
//
//    public function addImage(Image $image): self
//    {
//        if (!$this->images->contains($image)) {
//            $this->images[] = $image;
//            $image->setProduct($this);
//        }
//
//        return $this;
//    }
//
//    public function removeImage(Image $image): self
//    {
//        if ($this->images->removeElement($image)) {
//            // set the owning side to null (unless already changed)
//            if ($image->getProduct() === $this) {
//                $image->setProduct(null);
//            }
//        }
//
//        return $this;
//    }

}