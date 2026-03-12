<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $CreatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $UpdatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    #[ORM\PrePersist]
    public function onCreatedAt(): static
    {
        $this->CreatedAt = new \DateTime("now");
        return $this;
    }


    public function getCreatedAt(): ?\DateTime
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(?\DateTime $CreatedAt): static
    {
        $this->CreatedAt = $CreatedAt;

        return $this;
    }

    #[ORM\PreUpdate]
    public function onUpdatedAt(): static
    {
        $this->UpdatedAt = new \DateTime("now");
        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->UpdatedAt;
    }

    public function setUpdatedAt(?\DateTime $UpdatedAt): static
    {
        $this->UpdatedAt = $UpdatedAt;

        return $this;
    }

}
