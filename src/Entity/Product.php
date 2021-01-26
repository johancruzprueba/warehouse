<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10, unique=true)
     * @Assert\Length(
          *      min = 4,
          *      max = 10,
          *      minMessage = "su codigo debe tener minimo {{ limit }} characteres ",
          *      maxMessage = "su codigo name no debe tener más de {{ limit }} characteres"
          * )
     * @Assert\Type(type="alnum")
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @Assert\Length(
         *      min = 4,
         *      minMessage = "El nombre debe tener minimo {{ limit }} characteres ",
         * )
     * @Assert\NotNull
     * @Assert\Type(type={"alpha", "digit"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=1000)
     * @Assert\Length(
         *      min = 1,
         *      minMessage = "La descripción debe tener al menos {{ limit }} caracteres",
         * )
     */
    private $description;

    /**
     * @ORM\Column(type="decimal", precision=12, scale=3)
     * @Assert\Length(
         *      min = 1,
         *      minMessage = "El precio debe tener minimo {{ limit }} characteres ",
         * )
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\Length(
         *      min = 1,
         *      minMessage = "la marca debe tener al menos {{ limit }} characteres",
         * )
     */
    private $brand;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="no")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
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

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getCategory(): ?category
    {
        return $this->category;
    }

    public function setCategory(?category $category): self
    {
        $this->category = $category;

        return $this;
    }





}
