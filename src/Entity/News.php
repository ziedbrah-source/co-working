<?php

namespace App\Entity;

use App\Repository\NewsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NewsRepository::class)
 */
class News
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=15000)
     */
    private $text;

    /**
     * @ORM\Column(type="datetime")
     */
    private $WrittenAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getWrittenAt(): ?\DateTimeInterface
    {
        $newDate = $this->WrittenAt;
        $newDate = $newDate->format('d/m/Y @ G:i');
        return $newDate;
    }

    public function setWrittenAt(\DateTimeInterface $WrittenAt): self
    {
        $this->WrittenAt = $WrittenAt;

        return $this;
    }
}
