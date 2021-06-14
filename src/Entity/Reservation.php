<?php

namespace App\Entity;

use App\Repository\ReservationRepository;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Reservation
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\GreaterThan("today", message="la date d'arrivée doit être ultérieure a la date d'aujourd'hui")
     */
    private $date_debut;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\GreaterThan(propertyPath="date_debut", message="la date de depart doit etre plus eloigné que la date d'arrivé")
     */
    private $date_fin;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $motif;

    /**
     * @ORM\Column(type="string", length=300, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Salle::class, inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $salle;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $prix;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\PrePersist
     */
    public function prePersist(){
if(empty($this->createdAt)){
    $this->createdAt=new \DateTime();
}
if(empty($this->prix)){
   $this->prix= $this->salle->getPrix() * $this->getDuration();
}
    }
    public function getDuration(){
        $diff =$this->date_fin->diff($this->date_debut);
        return $diff->days;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getdate_debut(): ?string
    {
       // $this->date_debut->getTimeStamp();
        $newDate = $this->date_debut;
        $newDate = $newDate->format('d/m/Y @ G:i');
        return $newDate;
    }public function getDateDebut(): ?\DateTimeInterface
{
       // $this->date_debut->getTimeStamp();
        $newDate = $this->date_debut;

        return $newDate;
    }
    public function setDateDebut(\DateTimeInterface $date_debut): self
    {
        $this->date_debut = $date_debut;
        return $this;
    }
    public function setdate_debut(\DateTimeInterface $date_debut): self
    {
        $this->date_debut = date_debut;
        return $this;
    }

    public function getdate_fin(): ?string
    {
        $newDate = $this->date_fin;
        $newDate = $newDate->format('d/m/Y @ G:i');
        return $newDate;
    }    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setdate_fin(\DateTimeInterface $date_fin): self
    {
        $this->date_fin = $date_fin;

        return $this;
    }
    public function setDateFin(\DateTimeInterface $date_fin): self
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(string $motif): self
    {
        $this->motif = $motif;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSalle(): ?Salle
    {
        return $this->salle;
    }

    public function setSalle(?Salle $salle): self
    {
        $this->salle = $salle;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }
    public function getUserId() : ?int {
        return $this->getUser()->getId();
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
    public function isBookableDays(){
        $notAvailableDays=$this->salle->getNotAvailableDays();
        $bookingDays = $this->getDays();

        $days= array_map(function($day){
            return $day->format('Y-m-d');
        },$bookingDays);
        $notAvailable =array_map(function($day){
            return $day->format('Y-m-d');
        },$notAvailableDays);
        foreach ($days as $day){
            if(array_search($day,$notAvailable) !== false)return false;
        }
        return true;
    }

    /**
     * @return array
     */
    public function getDays(){
        $resultat=range(
            $this->date_debut->getTimestamp(),
            $this->date_fin->getTimestamp(),
            24*60*60
        );
        $days =array_map(function($dayTimestamp){
            return new \DateTime(date('Y-m-d',$dayTimestamp));
        },$resultat);
        return $days;
    }
}
