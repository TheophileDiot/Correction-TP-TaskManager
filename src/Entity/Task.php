<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Entité Task (Tâche)
 * 
 * Cette classe représente une tâche dans l'application de gestion de tâches.
 * Chaque tâche possède un titre, une description optionnelle, un état (terminée ou non)
 * et une date de création.
 */
#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Task
{
    /**
     * Identifiant unique de la tâche
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Titre de la tâche
     * Ne peut pas être vide et doit avoir entre 2 et 255 caractères
     */
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le titre ne peut pas être vide")]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: "Le titre doit contenir au moins {{ limit }} caractères",
        maxMessage: "Le titre ne peut pas dépasser {{ limit }} caractères"
    )]
    private ?string $title = null;

    /**
     * Description détaillée de la tâche (optionnelle)
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * État de la tâche (true = terminée, false = en cours)
     */
    #[ORM\Column]
    private bool $isDone = false;

    /**
     * Date et heure de création de la tâche
     */
    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    /**
     * Constructeur de la classe Task
     * Initialise la date de création avec la date et l'heure actuelles
     */
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    /**
     * Récupère l'identifiant de la tâche
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Récupère le titre de la tâche
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Définit le titre de la tâche
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Récupère la description de la tâche
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Définit la description de la tâche
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Vérifie si la tâche est terminée
     */
    public function isDone(): bool
    {
        return $this->isDone;
    }

    /**
     * Récupère l'état de la tâche (terminée ou non)
     */
    public function getIsDone(): bool
    {
        return $this->isDone;
    }

    /**
     * Définit l'état de la tâche (terminée ou non)
     */
    public function setIsDone(bool $isDone): self
    {
        $this->isDone = $isDone;

        return $this;
    }

    /**
     * Récupère la date de création de la tâche
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Définit la date de création de la tâche
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
