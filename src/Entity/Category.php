<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Validation;



/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @Assert\Type(type="alnum")
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @Assert\Length(
          *      min = 2,
          *      minMessage = "El nombre debe tener minimo {{ limit }} caracteres",
          * )
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="category")
     */
    private $no;

    public function __construct()
    {
        $this->no = new ArrayCollection();
    }

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

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }



   public function __toString()
   {
       return $this->name;
   }

   /**
    * @return Collection|Product[]
    */
   public function getNo(): Collection
   {
       return $this->no;
   }

   public function addNo(Product $no): self
   {
       if (!$this->no->contains($no)) {
           $this->no[] = $no;
           $no->setCategory($this);
       }

       return $this;
   }

   public function removeNo(Product $no): self
   {
       if ($this->no->removeElement($no)) {
           // set the owning side to null (unless already changed)
           if ($no->getCategory() === $this) {
               $no->setCategory(null);
           }
       }

       return $this;
   }



}
