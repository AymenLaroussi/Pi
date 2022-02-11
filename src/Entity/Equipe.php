<?php

namespace App\Entity;

use App\Repository\EquipeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EquipeRepository::class)
 */
class Equipe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $match_gagne;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $vainqueur;

    /**
     * @ORM\ManyToOne(targetEntity=Tournoi::class, inversedBy="equipes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tournoi;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getMatchGagne(): ?int
    {
        return $this->match_gagne;
    }

    public function setMatchGagne(?int $match_gagne): self
    {
        $this->match_gagne = $match_gagne;

        return $this;
    }

    public function getVainqueur(): ?bool
    {
        return $this->vainqueur;
    }

    public function setVainqueur(?bool $vainqueur): self
    {
        $this->vainqueur = $vainqueur;

        return $this;
    }

    public function getTournoi(): ?Tournoi
    {
        return $this->tournoi;
    }

    public function setTournoi(?Tournoi $tournoi): self
    {
        $this->tournoi = $tournoi;

        return $this;
    }
}
