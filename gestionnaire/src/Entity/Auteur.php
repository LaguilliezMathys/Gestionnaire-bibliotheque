<?php

namespace App\Entity;

use App\Repository\AuteurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: AuteurRepository::class)]
class Auteur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['auteur:read', 'auteur:list', 'livre:read', 'livre:list'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['auteur:read', 'auteur:list', 'livre:read', 'livre:list'])]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    #[Groups(['auteur:read', 'auteur:list', 'livre:read', 'livre:list'])]
    private ?string $prenom = null;

    #[ORM\Column(type: 'date', nullable: true)]
    #[Groups(['auteur:read', 'auteur:list'])]
    private ?\DateTimeInterface $dateNaissance = null;

    #[ORM\Column(type: 'date', nullable: true)]
    #[Groups(['auteur:read'])]
    private ?\DateTimeInterface $dateDeces = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(['auteur:read', 'auteur:list'])]
    private ?string $nationalite = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['auteur:read', 'auteur:list', 'livre:read'])]
    private ?string $photo = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['auteur:read'])]
    private ?string $description = null;

    /**
     * @var Collection<int, Livre>
     */
    #[ORM\ManyToMany(targetEntity: Livre::class, mappedBy: 'auteurs')]
    #[Groups(['auteur:read'])]
    private Collection $livres;

    public function __construct()
    {
        $this->livres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(?\DateTimeInterface $dateNaissance): static
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getDateDeces(): ?\DateTimeInterface
    {
        return $this->dateDeces;
    }

    public function setDateDeces(?\DateTimeInterface $dateDeces): static
    {
        $this->dateDeces = $dateDeces;

        return $this;
    }

    public function getNationalite(): ?string
    {
        return $this->nationalite;
    }

    public function setNationalite(?string $nationalite): static
    {
        $this->nationalite = $nationalite;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @return Collection<int, Livre>
     */
    public function getLivres(): Collection
    {
        return $this->livres;
    }

    public function addLivre(Livre $livre): static
    {
        if (!$this->livres->contains($livre)) {
            $this->livres->add($livre);
            $livre->addAuteur($this);
        }

        return $this;
    }

    public function removeLivre(Livre $livre): static
    {
        if ($this->livres->removeElement($livre)) {
            $livre->removeAuteur($this);
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }
}
