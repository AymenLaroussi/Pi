<?php

namespace App\Entity;

use App\Repository\TournoiRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TournoiRepository::class)
 */
class Tournoi
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $nom;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $nbr_equipes;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $nbr_joueur_eq;

    /**
     * @ORM\Column(type="float", nullable=true)
     * * @Assert\NotBlank()
     *
     *
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Url(message = "The url '{{ value }}' is not a valid url",)
     */
    private $discord_channel;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\DateTime
     * @var string A "Y-m-d H:i:s" formatted value
     */
    private $time;

    /**
     * @ORM\OneToMany(targetEntity=Equipe::class, mappedBy="tournoi", orphanRemoval=true)
     */
    private $equipes;



    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tournois")
     * @ORM\JoinColumn(name="organisateur_id", referencedColumnName="id",onDelete="SET NULL")
     */
    private $organisateur;

    /**
     * @ORM\ManyToOne(targetEntity=Jeu::class, inversedBy="tournois")
     * @ORM\JoinColumn(name="jeu_id", referencedColumnName="id",onDelete="SET NULL")
     */
    private $jeu;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $textColor;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $timeEnd;



    public function __construct()
    {
        $this->equipes = new ArrayCollection();
    }

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

    public function getNbrEquipes(): ?int
    {
        return $this->nbr_equipes;
    }

    public function setNbrEquipes(int $nbr_equipes): self
    {
        $this->nbr_equipes = $nbr_equipes;

        return $this;
    }

    public function getNbrJoueurEq(): ?int
    {
        return $this->nbr_joueur_eq;
    }

    public function setNbrJoueurEq(int $nbr_joueur_eq): self
    {
        $this->nbr_joueur_eq = $nbr_joueur_eq;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|Equipe[]
     */
    public function getEquipes(): Collection
    {
        return $this->equipes;
    }

    public function addEquipe(Equipe $equipe): self
    {
        if (!$this->equipes->contains($equipe)) {
            $this->equipes[] = $equipe;
            $equipe->setTournoi($this);
        }

        return $this;
    }

    public function removeEquipe(Equipe $equipe): self
    {
        if ($this->equipes->removeElement($equipe)) {
            // set the owning side to null (unless already changed)
            if ($equipe->getTournoi() === $this) {
                $equipe->setTournoi(null);
            }
        }

        return $this;
    }

    public function getDiscordChannel(): ?string
    {
        return $this->discord_channel;
    }

    public function setDiscordChannel(string $discord_channel): self
    {
        $this->discord_channel = $discord_channel;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(?\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }





    public function getOrganisateur(): ?User
    {
        return $this->organisateur;
    }

    public function setOrganisteur(?User $organisateur): self
    {
        $this->organisateur = $organisateur;

        return $this;
    }

    public function getJeu(): ?Jeu
    {
        return $this->jeu;
    }

    public function setJeu(?Jeu $jeu): self
    {
        $this->jeu = $jeu;

        return $this;
    }

    public function getTextColor(): ?string
    {
        return $this->textColor;
    }

    public function setTextColor(?string $textColor): self
    {
        $this->textColor = $textColor;

        return $this;
    }

    public function getTimeEnd(): ?\DateTimeInterface
    {
        return $this->timeEnd;
    }

    public function setTimeEnd(?\DateTimeInterface $timeEnd): self
    {
        $this->timeEnd = $timeEnd;

        return $this;
    }
}
