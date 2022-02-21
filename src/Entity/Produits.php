<?php

namespace App\Entity;

use App\Repository\ProduitsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProduitsRepository::class)
 */
class Produits
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull (message="Titre du produit est obligatoire.")
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Positive(message="Promotion du produit est doit étre positive.")
     */
    private $promo;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Positive(message="Quantité du produit est doit étre positive.")
     */
    private $stock;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $flash;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull (message="Image du produit est obligatoire.")
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull (message="Réference du produit est  obligatoire.")
     */
    private $ref;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull (message="Description du produit est obligatoire.")
     */
    private $longdescription;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotNull (message="Prix du produit est obligatoire.")
     * @Assert\Type(type="float", message="Prix doit étre positive")
     */
    private $prix;

    /**
     * @ORM\ManyToOne(targetEntity=Categories::class, inversedBy="produits")
     * @ORM\JoinColumn(nullable=false)
     * 
     */
    private $categories;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(string $ref): self
    {
        $this->ref = $ref;

        return $this;
    }

    public function getLongdescription(): ?string
    {
        return $this->longdescription;
    }

    public function setLongdescription(string $longdescription): self
    {
        $this->longdescription = $longdescription;

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

    public function getPromo(): ?int
    {
        return $this->promo;
    }

    public function setPromo(int $promo): self
    {
        $this->promo = $promo;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getFlash(): ?bool
    {
        return $this->flash;
    }

    public function setFlash(bool $flash): self
    {
        $this->flash = $flash;

        return $this;
    }
    
    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }
    

    public function getCategories(): ?Categories
    {
        return $this->categories;
    }

    public function setCategories(?Categories $categories): self
    {
        $this->categories = $categories;

        return $this;
    }
    public function __toString()
    {
        return(string)$this->getTitre();
    }
}
