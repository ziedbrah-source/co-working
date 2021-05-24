<?php

namespace App\Entity;

use App\Repository\SalleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SalleRepository::class)
 */
class Salle
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
    private $nom;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbr_tables;

    /**
     * @ORM\Column(type="boolean")
     */
    private $data_show;

    /**
     * @ORM\Column(type="boolean")
     */
    private $climatisation;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbr_tableaux;

    /**
     * @ORM\Column(type="float")
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="salle")
     */
    private $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
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

    public function getNbrTables(): ?int
    {
        return $this->nbr_tables;
    }

    public function setNbrTables(int $nbr_tables): self
    {
        $this->nbr_tables = $nbr_tables;

        return $this;
    }

    public function getDataShow(): ?bool
    {
        return $this->data_show;
    }

    public function setDataShow(bool $data_show): self
    {
        $this->data_show = $data_show;

        return $this;
    }

    public function getClimatisation(): ?bool
    {
        return $this->climatisation;
    }

    public function setClimatisation(bool $climatisation): self
    {
        $this->climatisation = $climatisation;

        return $this;
    }

    public function getNbrTableaux(): ?int
    {
        return $this->nbr_tableaux;
    }

    public function setNbrTableaux(int $nbr_tableaux): self
    {
        $this->nbr_tableaux = $nbr_tableaux;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setSalle($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getSalle() === $this) {
                $reservation->setSalle(null);
            }
        }

        return $this;
    }
}
