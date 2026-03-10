<?php

namespace App\Entity;

use App\Repository\EmpruntRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: EmpruntRepository::class)]
class Emprunt
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['emprunt:read'])]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['emprunt:read'])]
    private ?\DateTimeInterface $dateEmprunt = null;

    #[ORM\Column(type: 'date')]
    #[Groups(['emprunt:read'])]
    private ?\DateTimeInterface $dateRetourPrevue = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(['emprunt:read'])]
    private ?\DateTimeInterface $dateRetourEffective = null;

    #[ORM\ManyToOne(inversedBy: 'emprunts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['emprunt:read'])]
    private ?Adherent $adherent = null;

    #[ORM\ManyToOne(inversedBy: 'emprunts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['emprunt:read'])]
    private ?Livre $livre = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateEmprunt(): ?\DateTimeInterface
    {
        return $this->dateEmprunt;
    }

    public function setDateEmprunt(\DateTimeInterface $dateEmprunt): static
    {
        $this->dateEmprunt = $dateEmprunt;

        return $this;
    }

    public function getDateRetourPrevue(): ?\DateTimeInterface
    {
        return $this->dateRetourPrevue;
    }

    public function setDateRetourPrevue(\DateTimeInterface $dateRetourPrevue): static
    {
        $this->dateRetourPrevue = $dateRetourPrevue;

        return $this;
    }

    public function getDateRetourEffective(): ?\DateTimeInterface
    {
        return $this->dateRetourEffective;
    }

    public function setDateRetourEffective(?\DateTimeInterface $dateRetourEffective): static
    {
        $this->dateRetourEffective = $dateRetourEffective;

        return $this;
    }

    public function getAdherent(): ?Adherent
    {
        return $this->adherent;
    }

    public function setAdherent(?Adherent $adherent): static
    {
        $this->adherent = $adherent;

        return $this;
    }

    public function getLivre(): ?Livre
    {
        return $this->livre;
    }

    public function setLivre(?Livre $livre): static
    {
        $this->livre = $livre;

        return $this;
    }

    /**
     * Vérifie si l'emprunt est en retard
     */
    public function isEnRetard(): bool
    {
        if ($this->dateRetourEffective !== null) {
            return false; // déjà rendu
        }

        return new \DateTime() > $this->dateRetourPrevue;
    }

    /**
     * Vérifie si l'emprunt est en cours (non rendu)
     */
    public function isEnCours(): bool
    {
        return $this->dateRetourEffective === null;
    }

    public function __toString(): string
    {
        return 'Emprunt #' . $this->id . ' - ' . ($this->livre ? $this->livre->getTitre() : '');
    }
}
