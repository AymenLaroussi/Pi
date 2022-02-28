<?php

namespace App\Entity;

use App\Repository\SponsorsRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=SponsorsRepository::class)
 */
class Sponsors
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

   /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Le champ est obligatoire")
     */
    private $nom;

   /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Le champ est obligatoire")
     */
    private $prenom;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Le champ ne dois pas etre vide" )
     *  @Assert\NotNull(message="Ne doit pas etre null ")
     *  @Assert\Length(
     *      min = 8,
     *      max = 10,
     *      minMessage = "Numero tel doit etre minimum 8 chiffres",
     *      maxMessage = " Numero tel doit etre max 10 chiffres")
     *  @Assert\PositiveOrZero
     */
    private $num;

    /**
     * @ORM\Column(type="float")
     * 
     * @Assert\NotBlank(message="Le champ est obligatoire")
     * @Assert\PositiveOrZero
     
     */
    private $budget;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Assert\Image(
     *     minWidth = 200,
     *     maxWidth = 400,
     *     minHeight = 200,
     *     maxHeight = 400
     * )
     */
    private $image;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNum(): ?int
    {
        return $this->num;
    }

    public function setNum(int $num): self
    {
        $this->num = $num;

        return $this;
    }

    public function getBudget(): ?float
    {
        return $this->budget;
    }

    public function setBudget(float $budget): self
    {
        $this->budget = $budget;

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

    

}
