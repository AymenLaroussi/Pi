<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EvenementRepository::class)
 */
class Evenement
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
    private $nomeven;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\NotBlank (message="Le champ est obligatoire")
     */
    private $lieuevent;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Assert\NotBlank(message="La date est obligatoire")
     * @Assert\NotNull(message="Ne doit pas etre null ")
     * @Assert\Date
     */
    private ?\DateTimeInterface  $datevent;

    /**
     * @ORM\Column(type="time")
     * * @Assert\NotBlank(message="Veuillez precisez l'heure ")
     * @Assert\NotNull(message="Ne doit pas etre null ")
     *  @Assert\Time
     * @var string A "H:i:s" formatted value
     */
    private $heurevent;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Assert\NotBlank(message="Le champ est obligatoire")
     * @Assert\NotNull(message="Ne doit pas etre null ")
    
     * @Assert\Date
     * @var string A "Y-m-d" formatted value
     */
    private ?\DateTimeInterface  $datefin;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull(message="Ne doit pas etre null ")
     *  @Assert\NotBlank( message="Le champ est obligatoire")
     *  @Assert\PositiveOrZero(message="Le champ est obligatoire")
     */
    private $nbrplace;

    /**
     * @ORM\Column(type="string", length=200)
     * 
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\NotBlank( message="Le champ est obligatoire")
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=Sponsors::class, inversedBy="evenements", fetch="EAGER")
     */
    private $sponsors;

    public function __construct()
    {
        $this->sponsors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getNomeven(): ?string
    {
        return $this->nomeven;
    }

    public function setNomeven(string $nomeven): self
    {
        $this->nomeven = $nomeven;

        return $this;
    }

    public function getLieuevent(): ?string
    {
        return $this->lieuevent;
    }

    public function setLieuevent(string $lieuevent): self
    {
        $this->lieuevent = $lieuevent;

        return $this;
    }

    public function getDatevent(): ?\DateTimeInterface
    {
        return $this->datevent;
    }

    public function setDatevent(?\DateTimeInterface $datevent): ?self
    {
        $this->datevent = $datevent;

        return $this;
    }

    public function getHeurevent(): ?\DateTimeInterface
    {
        return $this->heurevent;
    }

    public function setHeurevent(?\DateTimeInterface $heurevent): ?self
    {
        $this->heurevent = $heurevent;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(?\DateTimeInterface $datefin): ?self
    {
        $this->datefin = $datefin;

        return $this;
    }

    public function getNbrplace(): ?int
    {
        return $this->nbrplace;
    }

    public function setNbrplace(int $nbrplace): self
    {
        $this->nbrplace = $nbrplace;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Sponsors[]
     */
    public function getSponsors(): Collection
    {
        return $this->sponsors;
    }

    public function addSponsor(Sponsors $sponsor): self
    {
        if (!$this->sponsors->contains($sponsor)) {
            $this->sponsors[] = $sponsor;
        }

        return $this;
    }

    public function removeSponsor(Sponsors $sponsor): self
    {
        $this->sponsors->removeElement($sponsor);

        return $this;
    }
}
