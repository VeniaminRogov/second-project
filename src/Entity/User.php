<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'boolean')]
    private $isVerified;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Orders::class)]
    private $odersList;

//    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Orders::class)]
//    private $orders;
    /**
     * @var null
     */
    private $plainPassword;

    public function __construct()
    {
        $this->products = new ArrayCollection();
//        $this->orders = new ArrayCollection();
        $this->odersList = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
         $this->plainPassword = null;
    }


    public function getIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

//    /**
//     * @return Collection|Orders[]
//     */
//    public function getOrders(): Collection
//    {
//        return $this->orders;
//    }
//
//    public function addOrder(Orders $order): self
//    {
//        if (!$this->orders->contains($order)) {
//            $this->orders[] = $order;
//            $order->setUser($this);
//        }
//
//        return $this;
//    }
//
//    public function removeOrder(Orders $order): self
//    {
//        if ($this->orders->removeElement($order)) {
//            // set the owning side to null (unless already changed)
//            if ($order->getUser() === $this) {
//                $order->setUser(null);
//            }
//        }
//
//        return $this;
//    }

/**
 * @return Collection|Orders[]
 */
public function getOdersList(): Collection
{
    return $this->odersList;
}

public function addOdersList(Orders $odersList): self
{
    if (!$this->odersList->contains($odersList)) {
        $this->odersList[] = $odersList;
        $odersList->setUser($this);
    }

    return $this;
}

public function removeOdersList(Orders $odersList): self
{
    if ($this->odersList->removeElement($odersList)) {
        // set the owning side to null (unless already changed)
        if ($odersList->getUser() === $this) {
            $odersList->setUser(null);
        }
    }

    return $this;
}

    /**
     * @return null
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }
}
